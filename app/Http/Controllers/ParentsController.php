<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Database\Schema\SchemaManager;
use TCG\Voyager\Events\BreadDataAdded;
use TCG\Voyager\Events\BreadDataDeleted;
use TCG\Voyager\Events\BreadDataRestored;
use TCG\Voyager\Events\BreadDataUpdated;
use TCG\Voyager\Events\BreadImagesDeleted;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;
use App\Models\User;
use App\Models\Parents;

class ParentsController extends VoyagerBaseController
{
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

            // Check permission
            $this->authorize('add', app($dataType->model_name));

            // Validate fields
            $val = $this->validateBread($request->all(), $dataType->addRows)->validate();
            $data = $this->insertUpdateData($request, $slug, $dataType->addRows, new $dataType->model_name());

            event(new BreadDataAdded($dataType, $data));

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

    public function show(Request $request, $id)
    {
        $slug = $this->getSlug($request);
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('read', app($dataType->model_name));

        $parent = Parents::with(['user', 'students'])->findOrFail($id);

        $view = 'vendor.voyager.parents.view';

        return Voyager::view($view, compact('dataType', 'parent'));
    }
}
