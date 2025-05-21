<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Parents;
use App\Http\Controllers\CustomVoyagerBaseController;

class ParentsController extends CustomVoyagerBaseController
{
    // This controller inherits all the methods from VoyagerBaseController
    // We only need to override specific methods that need custom behavior

    public function create(Request $request)
    {
        // This method is called when accessing the /admin/parents/create route
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission with fallback for admin
        if (!auth()->user() || (auth()->user() && (!method_exists(auth()->user(), 'isAdmin') || !auth()->user()->isAdmin()))) {
            $this->authorize('add', app($dataType->model_name));
        }

        $dataTypeContent = (strlen($dataType->model_name) != 0)
                            ? new $dataType->model_name()
                            : false;

        foreach ($dataType->addRows as $key => $row) {
            $dataType->addRows[$key]['col_width'] = $row->details->width ?? 100;
        }

        // If a column has a relationship associated with it, we do not want to show that field
        $this->removeRelationshipField($dataType, 'add');

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($dataTypeContent);

        // Use the default bread edit-add view
        return Voyager::view('voyager::bread.edit-add', compact(
            'dataType',
            'dataTypeContent',
            'isModelTranslatable'
        ));
    }

    public function store(Request $request)
    {
        // Begin transaction to ensure all related records are created together
        DB::beginTransaction();

        try {
            // Create user account for parent
            $user = new User();
            $user->name = $request->father_name;
            $user->email = $request->father_email ?? $request->mother_email;
            $user->password = bcrypt($request->password ?? 'password123');
            $user->role_id = 4; // Parent role ID
            $user->save();

            // Add user_id to the parent data
            $request->merge(['user_id' => $user->id]);

            // Continue with the normal BREAD storing process
            $slug = $this->getSlug($request);
            $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

            // Skip authorization check if user is admin, otherwise check permissions
            $user = auth()->user();
            $isAdmin = $user && method_exists($user, 'hasRole') && $user->hasRole('admin');
            if (!$isAdmin) {
                $this->authorize('add', app($dataType->model_name));
            }

            // Validate fields
            $val = $this->validateBread($request->all(), $dataType->addRows)->validate();
            $data = $this->insertUpdateData($request, $slug, $dataType->addRows, new $dataType->model_name());

            event(new \TCG\Voyager\Events\BreadDataAdded($dataType, $data));

            DB::commit();

            return redirect()
                ->route("voyager.{$dataType->slug}.index")
                ->with([
                    'message'    => __('voyager::generic.successfully_added_new')." {$dataType->getTranslatedAttribute('display_name_singular')}",
                    'alert-type' => 'success',
                ]);
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->with([
                'message'    => 'Error creating parent: ' . $e->getMessage(),
                'alert-type' => 'error',
            ]);
        }
    }

    public function index(Request $request)
    {
        // GET THE SLUG, ex. 'posts', 'pages', etc.
        $slug = $this->getSlug($request);

        // GET THE DataType based on the slug
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Skip authorization check if user is admin, otherwise check permissions
        $user = auth()->user();
        $isAdmin = $user && method_exists($user, 'hasRole') && $user->hasRole('admin');
        if (!$isAdmin) {
            $this->authorize('browse', app($dataType->model_name));
        }

        $getter = $dataType->server_side ? 'paginate' : 'get';

        // Define isServerSide
        $isServerSide = isset($dataType->server_side) && $dataType->server_side;

        $search = (object) ['value' => $request->get('s'), 'key' => $request->get('key'), 'filter' => $request->get('filter')];

        $searchNames = [];
        if ($dataType->server_side) {
            $searchNames = $dataType->browseRows->mapWithKeys(function ($row) {
                return [$row['field'] => $row->getTranslatedAttribute('display_name')];
            });
        }

        $orderBy = $request->get('order_by', $dataType->order_column);
        $sortOrder = $request->get('sort_order', $dataType->order_direction);

        // Define usesSoftDeletes and showSoftDeleted
        $usesSoftDeletes = false;
        $showSoftDeleted = false;

        // Define isServerSide
        $isServerSide = isset($dataType->server_side) && $dataType->server_side;

        // Next Get or Paginate the actual content from the MODEL that corresponds to the slug DataType
        if (strlen($dataType->model_name) != 0) {
            // Load the model class
            $modelClass = $dataType->model_name;
            $model = new $modelClass();

            // Check if the model uses soft deletes
            $usesSoftDeletes = in_array(SoftDeletes::class, class_uses_recursive($modelClass));

            // Get query for the model
            $query = $modelClass::select("{$dataType->name}.*");

            // If a column has a relationship associated with it, we do not want to show that field
            $this->removeRelationshipField($dataType, 'browse');

            if ($search->value != '' && $search->key && $search->filter) {
                $search_filter = ($search->filter == 'equals') ? '=' : 'LIKE';
                $search_value = ($search->filter == 'equals') ? $search->value : '%'.$search->value.'%';

                $query->where($search->key, $search_filter, $search_value);
            }

            if ($orderBy && in_array($orderBy, $dataType->fields())) {
                $querySortOrder = (!empty($sortOrder)) ? $sortOrder : 'desc';
                $query->orderBy($orderBy, $querySortOrder);
            }

            // Add the relationship
            $query->with(['user']);

            // Get the data
            $dataTypeContent = call_user_func([$query, $getter]);

        } else {
            // If Model doesn't exist, get data from table name
            $dataTypeContent = call_user_func([DB::table($dataType->name), $getter]);
        }

        // Replace relationships' keys for labels and create READ links
        $dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType);

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($model);

        // Actions
        $actions = [];
        $firstItem = is_object($dataTypeContent) && method_exists($dataTypeContent, 'first') ? $dataTypeContent->first() : null;
        if (!empty($firstItem)) {
            foreach (Voyager::actions() as $action) {
                $action = new $action($dataType, $firstItem);

                if ($action->shouldActionDisplayOnDataType()) {
                    $actions[] = $action;
                }
            }
        }

        // Define showCheckboxColumn
        $showCheckboxColumn = false;
        if (Auth::check() && method_exists(Auth::user(), 'hasPermission') && Auth::user()->hasPermission('delete_' . $dataType->name)) {
            $showCheckboxColumn = true;
        } else {
            foreach ($actions as $action) {
                if (method_exists($action, 'massAction')) {
                    $showCheckboxColumn = true;
                }
            }
        }

        // Define orderColumn
        $orderColumn = [];
        if ($orderBy) {
            $index = $dataType->browseRows->where('field', $orderBy)->keys()->first() + ($showCheckboxColumn ? 1 : 0);
            $orderColumn = [[$index, $sortOrder ?? 'desc']];
        }

        // Define list of columns that can be sorted server side
        $sortableColumns = $this->getSortableColumns($dataType->browseRows);

        // Define default search key
        $defaultSearchKey = $dataType->default_search_key ?? null;

        // Use the default bread browse view
        return Voyager::view('voyager::bread.browse', compact(
            'dataType',
            'dataTypeContent',
            'isModelTranslatable',
            'search',
            'orderBy',
            'sortOrder',
            'searchNames',
            'usesSoftDeletes',
            'showSoftDeleted',
            'actions',
            'showCheckboxColumn',
            'orderColumn',
            'sortableColumns',
            'isServerSide',
            'defaultSearchKey'
        ));
    }

    public function edit(Request $request, $id)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        $dataTypeContent = (strlen($dataType->model_name) != 0)
            ? app($dataType->model_name)->findOrFail($id)
            : DB::table($dataType->name)->where('id', $id)->first();

        foreach ($dataType->editRows as $key => $row) {
            $dataType->editRows[$key]['col_width'] = isset($row->details->width) ? $row->details->width : 100;
        }

        // If a column has a relationship associated with it, we do not want to show that field
        $this->removeRelationshipField($dataType, 'edit');

        // Skip authorization check if user is admin, otherwise check permissions
        $user = auth()->user();
        $isAdmin = $user && method_exists($user, 'hasRole') && $user->hasRole('admin');
        if (!$isAdmin) {
            $this->authorize('edit', $dataTypeContent);
        }

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($dataTypeContent);

        // Use the default bread edit-add view
        return Voyager::view('voyager::bread.edit-add', compact(
            'dataType',
            'dataTypeContent',
            'isModelTranslatable'
        ));
    }

    /**
     * Get sortable columns.
     *
     * @param array $rows
     *
     * @return array
     */
    protected function getSortableColumns($rows)
    {
        return collect($rows)->filter(function ($item) {
            if ($item->type != 'relationship') {
                return true;
            }
            if ($item->details->type != 'belongsTo') {
                return false;
            }

            return !$this->relationIsUsingAccessorAsLabel($item->details);
        })
        ->pluck('field')
        ->toArray();
    }
}
