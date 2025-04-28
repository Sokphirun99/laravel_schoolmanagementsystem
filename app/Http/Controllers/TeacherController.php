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
use App\Models\Teacher;
use App\Models\Section;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\Timetable;
use App\Models\Exam;
use App\Models\ExamResult;
use Carbon\Carbon;

class TeacherController extends VoyagerBaseController
{
    public function dashboard()
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();

        // Get assigned classes
        $assignedSections = $teacher->sections;

        // Get today's timetable
        $dayOfWeek = Carbon::now()->dayOfWeek; // 0 (Sunday) to 6 (Saturday)
        $todayTimetable = Timetable::with('section', 'subject')
            ->where('teacher_id', $teacher->id)
            ->where('day_of_week', $dayOfWeek)
            ->orderBy('start_time')
            ->get();

        // Get upcoming exams for teacher's subjects
        $upcomingExams = Exam::whereHas('subject', function($query) use ($teacher) {
                $query->whereHas('teachers', function($q) use ($teacher) {
                    $q->where('teacher_id', $teacher->id);
                });
            })
            ->where('exam_date', '>=', now())
            ->orderBy('exam_date')
            ->limit(5)
            ->get();

        // Count total students
        $totalStudents = Student::whereHas('section', function($query) use ($teacher) {
                $query->whereHas('teachers', function($q) use ($teacher) {
                    $q->where('teacher_id', $teacher->id);
                });
            })
            ->count();

        return view('teacher.dashboard', compact(
            'teacher',
            'assignedSections',
            'todayTimetable',
            'upcomingExams',
            'totalStudents'
        ));
    }

    public function profile()
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        return view('teacher.profile', compact('teacher'));
    }

    public function updateProfile(Request $request)
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'qualification' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|max:1024',
        ]);

        // Update teacher profile
        $teacher->phone = $request->phone;
        $teacher->address = $request->address;
        $teacher->qualification = $request->qualification;

        // Update avatar if uploaded
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('teachers/avatars', 'public');
            $teacher->avatar = $path;
        }

        $teacher->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function store(Request $request)
    {
        // Begin transaction to ensure all related records are created together
        DB::beginTransaction();

        try {
            // Create user account for teacher
            $user = new User();
            $user->name = $request->first_name . ' ' . $request->last_name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password ?? 'password123');
            $user->role_id = 2; // Teacher role ID
            $user->save();

            // Add user_id to the teacher data
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
                'message'    => 'Error creating teacher: ' . $e->getMessage(),
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

        $teacher = Teacher::with(['user', 'sections', 'subjects', 'timetables'])->findOrFail($id);

        $view = 'vendor.voyager.teachers.view';

        return Voyager::view($view, compact('dataType', 'teacher'));
    }
}
