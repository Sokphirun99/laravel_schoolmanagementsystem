<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
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
use App\Models\Role;
use App\Services\UserService;
use Carbon\Carbon;

class TeacherController extends VoyagerBaseController
{
    /**
     * Display teacher dashboard with relevant information.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard(): View
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();

        // Get assigned classes with eager loading
        $assignedSections = $teacher->sections()->with(['className', 'students'])->get();

        // Get today's timetable
        $dayOfWeek = Carbon::now()->dayOfWeek;
        $todayTimetable = Timetable::with(['section.className', 'subject'])
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
            ->with(['subject', 'classLevel'])
            ->where('exam_date', '>=', now())
            ->orderBy('exam_date')
            ->limit(5)
            ->get();

        // Count total students with optimized query
        $totalStudents = Student::whereHas('section', function($query) use ($teacher) {
                $query->whereIn('id', $teacher->sections->pluck('id'));
            })
            ->count();

        // Get recent attendance records
        $recentAttendance = Attendance::whereHas('section', function($query) use ($teacher) {
                $query->whereIn('id', $teacher->sections->pluck('id'));
            })
            ->with(['section', 'subject'])
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();

        return view('teacher.dashboard', compact(
            'teacher',
            'assignedSections',
            'todayTimetable',
            'upcomingExams',
            'totalStudents',
            'recentAttendance'
        ));
    }

    /**
     * Display teacher profile.
     *
     * @return \Illuminate\View\View
     */
    public function profile(): View
    {
        $teacher = Teacher::with('user')
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('teacher.profile', compact('teacher'));
    }

    /**
     * Update teacher profile information.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();

        $validated = $request->validate([
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'qualification' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'avatar' => 'nullable|image|max:2048',
        ]);

        // Update teacher profile
        $teacher->fill($validated);

        // Update avatar if uploaded
        if ($request->hasFile('avatar')) {
            // Remove old avatar if exists
            if ($teacher->avatar && file_exists(storage_path('app/public/' . $teacher->avatar))) {
                unlink(storage_path('app/public/' . $teacher->avatar));
            }

            $path = $request->file('avatar')->store('teachers/avatars', 'public');
            $teacher->avatar = $path;
        }

        $teacher->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Create a new teacher with user account.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // Begin transaction
        DB::beginTransaction();

        try {
            // Use UserService to create teacher user
            $userService = new UserService();
            $userData = [
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'password' => $request->password ?? 'password123'
            ];

            $teacherData = $request->except(['email', 'password']);

            $result = $userService->createTeacherUser($userData, $teacherData);

            if (!$result['success']) {
                throw new \Exception($result['message']);
            }

            // Add user_id to the request data
            $request->merge(['user_id' => $result['user']->id]);

            // Continue with the Voyager BREAD storing process
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

    /**
     * Display detailed view of a teacher.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show(Request $request, $id): View
    {
        $slug = $this->getSlug($request);
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('read', app($dataType->model_name));

        // Eager load all relationships for better performance
        $teacher = Teacher::with([
            'user',
            'sections.className',
            'subjects',
            'timetables.subject',
            'timetables.section'
        ])->findOrFail($id);

        // Get attendance statistics
        $attendanceStats = Attendance::where('teacher_id', $id)
            ->selectRaw('COUNT(*) as total, MONTH(date) as month')
            ->whereYear('date', now()->year)
            ->groupBy('month')
            ->get();

        $view = 'vendor.voyager.teachers.view';

        return Voyager::view($view, compact('dataType', 'teacher', 'attendanceStats'));
    }

    /**
     * Show teacher's timetable.
     *
     * @return \Illuminate\View\View
     */
    public function timetable(): View
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();

        $timetableByDay = [];
        for ($day = 0; $day <= 6; $day++) {
            $timetableByDay[$day] = Timetable::with(['section.className', 'subject'])
                ->where('teacher_id', $teacher->id)
                ->where('day_of_week', $day)
                ->orderBy('start_time')
                ->get();
        }

        return view('teacher.timetable', compact('teacher', 'timetableByDay'));
    }
}
