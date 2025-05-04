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
use App\Models\Student;
use App\Models\Attendance;
use App\Models\ExamResult;
use App\Models\StudentFee;
use App\Models\Timetable;
class StudentController extends VoyagerBaseController
{
    public function index(Request $request)
    {
        // GET THE SLUG, ex. 'posts', 'pages', etc.
        $slug = $this->getSlug($request);

        // GET THE DataType based on the slug
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('browse', app($dataType->model_name));

        $getter = $dataType->server_side ? 'paginate' : 'get';

        $search = (object) ['value' => $request->get('s'), 'key' => $request->get('key'), 'filter' => $request->get('filter')];

        $searchNames = [];
        if ($dataType->server_side) {
            $searchNames = $dataType->browseRows->mapWithKeys(function ($row) {
                return [$row['field'] => $row->getTranslatedAttribute('display_name')];
            });
        }

        $orderBy = $request->get('order_by', $dataType->order_column);
        $sortOrder = $request->get('sort_order', $dataType->order_direction);
        $usesSoftDeletes = false;
        $showSoftDeleted = false;

        // Next Get or Paginate the actual content from the MODEL that corresponds to the slug DataType
        if (strlen($dataType->model_name) != 0) {
            $model = app($dataType->model_name);

            $query = $model::select($dataType->name.'.*');

            if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope'.ucfirst($dataType->scope))) {
                $query->{$dataType->scope}();
            }

            // If a column has a relationship associated with it, we do not want to show that field
            $this->removeRelationshipField($dataType, 'browse');

            if ($search->value != '' && $search->key && $search->filter) {
                $search_filter = ($search->filter == 'equals') ? '=' : 'LIKE';
                $search_value = ($search->filter == 'equals') ? $search->value : '%'.$search->value.'%';

                $searchField = $dataType->name.'.'.$search->key;
                if ($row = $this->findSearchableRelationshipRow($dataType->rows->where('type', 'relationship'), $search->key)) {
                    $query->whereIn(
                        $dataType->name.'.'.$row->field,
                        $row->details->model::where($row->details->label, $search_filter, $search_value)->pluck('id')->toArray()
                    );
                } else {
                    if ($dataType->browseRows->pluck('field')->contains($search->key)) {
                        $query->where($searchField, $search_filter, $search_value);
                    }
                }
            }

            $row = $dataType->rows->where('field', $orderBy)->firstWhere('type', 'relationship');
            if ($orderBy && (in_array($orderBy, $dataType->fields()) || !empty($row))) {
                $querySortOrder = (!empty($sortOrder)) ? $sortOrder : 'desc';
                if (!empty($row)) {
                    $query->select([
                        $dataType->name.'.*',
                        'joined.'.$row->details->label.' as '.$orderBy,
                    ])->leftJoin(
                        $row->details->table.' as joined',
                        $dataType->name.'.'.$row->details->column,
                        'joined.'.$row->details->key
                    );
                }

                $dataTypeContent = call_user_func([
                    $query->orderBy($orderBy, $querySortOrder),
                    $getter,
                ]);
            } elseif ($model->timestamps) {
                $dataTypeContent = call_user_func([$query->latest($model::CREATED_AT), $getter]);
            } else {
                $dataTypeContent = call_user_func([$query->orderBy($model->getKeyName(), 'DESC'), $getter]);
            }

            // Replace relationships' keys for labels and create READ links if a slug is provided.
            $dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType);
        } else {
            // If Model doesn't exist, get data from table name
            $dataTypeContent = call_user_func([DB::table($dataType->name), $getter]);
            $model = false;
        }

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($model);

        // Eagerload Relations
        $this->eagerLoadRelations($dataTypeContent, $dataType, 'browse', $isModelTranslatable);

        // Check if server side pagination is enabled
        $isServerSide = isset($dataType->server_side) && $dataType->server_side;

        // Check if a default search key is set
        $defaultSearchKey = $dataType->default_search_key ?? null;

        // Actions
        $actions = [];
        if (!empty($dataTypeContent->first())) {
            foreach (Voyager::actions() as $action) {
                $action = new $action($dataType, $dataTypeContent->first());

                if ($action->shouldActionDisplayOnDataType()) {
                    $actions[] = $action;
                }
            }
        }

        // Define showCheckboxColumn
        $showCheckboxColumn = false;
        if (Auth::check() && Auth::user()->hasPermissionTo('delete_' . $dataType->name)) {
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

        $view = 'voyager::bread.browse';

        if (view()->exists("voyager::$slug.browse")) {
            $view = "voyager::$slug.browse";
        }

        return Voyager::view($view, compact(
            'actions',
            'dataType',
            'dataTypeContent',
            'isModelTranslatable',
            'search',
            'orderBy',
            'orderColumn',
            'sortableColumns',
            'sortOrder',
            'searchNames',
            'isServerSide',
            'defaultSearchKey',
            'usesSoftDeletes',
            'showSoftDeleted',
            'showCheckboxColumn'
        ));
    }

    public function dashboard()
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();

        // Get upcoming exams
        $upcomingExams = $student->section->exams()
            ->where('exam_date', '>=', now())
            ->orderBy('exam_date')
            ->limit(5)
            ->get();

        // Get recent attendance
        $recentAttendance = Attendance::where('student_id', $student->id)
            ->orderBy('date', 'desc')
            ->limit(10)
            ->get();

        // Get timetable
        $timetable = Timetable::where('section_id', $student->section_id)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        // Get upcoming fees
        $upcomingFees = StudentFee::where('student_id', $student->id)
            ->where('status', '!=', 'paid')
            ->orderBy('due_date')
            ->limit(5)
            ->get();

        return view('student.dashboard', compact(
            'student',
            'upcomingExams',
            'recentAttendance',
            'timetable',
            'upcomingFees'
        ));
    }

    public function profile()
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();
        return view('student.profile', compact('student'));
    }

    public function updateProfile(Request $request)
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|max:1024',
        ]);

        // Update student profile
        $student->phone = $request->phone;
        $student->address = $request->address;

        // Update avatar if uploaded
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('students/avatars', 'public');
            $student->user->avatar = $path;
            $student->user->save();
        }

        $student->save();

        return redirect()->route('student.profile')->with('success', 'Profile updated successfully');
    }

    public function viewAttendance()
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();
        $attendance = Attendance::where('student_id', $student->id)
            ->orderBy('date', 'desc')
            ->paginate(20);

        return view('student.attendance', compact('attendance'));
    }

    public function viewExamResults()
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();
        $examResults = ExamResult::with('exam', 'subject')
            ->where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('exam_id');

        return view('student.exam_results', compact('examResults'));
    }

    public function viewTimetable()
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();
        $timetable = Timetable::with('subject', 'teacher')
            ->where('section_id', $student->section_id)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        return view('student.timetable', compact('timetable'));
    }

    public function viewFees()
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();
        $fees = StudentFee::with('feeStructure')
            ->where('student_id', $student->id)
            ->orderBy('due_date')
            ->paginate(10);

        return view('student.fees', compact('fees'));
    }

    public function store(Request $request)
    {
        // Begin transaction to ensure all related records are created together
        DB::beginTransaction();

        try {
            // Create user account for student
            $user = new User();
            $user->name = $request->first_name . ' ' . $request->last_name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password ?? 'password123');
            $user->role_id = 3; // Student role ID
            $user->save();

            // Add user_id to the student data
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
                'message'    => 'Error creating student: ' . $e->getMessage(),
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

        $student = Student::with(['user', 'parent', 'section', 'attendances', 'results', 'fees'])->findOrFail($id);

        $view = 'vendor.voyager.students.view';

        return Voyager::view($view, compact('dataType', 'student'));
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
