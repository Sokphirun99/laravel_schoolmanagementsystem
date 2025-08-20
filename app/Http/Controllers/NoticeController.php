<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoticeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of notices.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = $this->buildNoticeQuery($user, $request);
        
        $notices = $query->orderBy('created_at', 'desc')->paginate(10);
        $classes = SchoolClass::all();
        
        return view('notices.index', compact('notices', 'classes'));
    }

    /**
     * Build notice query based on user role and filters
     */
    private function buildNoticeQuery($user, Request $request)
    {
        $query = Notice::query();
        
        // Apply role-based filters
        $this->applyRoleBasedFilters($query, $user);
        
        // Apply admin/teacher specific filters
        if ($user->isAdmin() || $user->isTeacher()) {
            $this->applyAdminTeacherFilters($query, $request);
        }
        
        // Show only active notices unless admin specifically requests all
        if (!$request->has('show_all') || !$user->isAdmin()) {
            $query->active();
        }
        
        return $query;
    }

    /**
     * Apply role-based filters to query
     */
    private function applyRoleBasedFilters($query, $user): void
    {
        if ($user->isStudent()) {
            $query->forAudience('student');
            if ($user->student?->class_id) {
                $query->byClass($user->student->class_id);
            }
        } elseif ($user->isTeacher()) {
            $query->forAudience('teacher');
        } elseif ($user->isParent()) {
            $query->forAudience('parent');
        }
    }

    /**
     * Apply admin/teacher specific filters
     */
    private function applyAdminTeacherFilters($query, Request $request): void
    {
        if ($request->has('audience')) {
            $query->where('target_audience', $request->audience);
        }
        
        if ($request->has('class_id') && $request->class_id > 0) {
            $query->where('class_id', $request->class_id);
        }
    }
    
    /**
     * Show the form for creating a new notice.
     */
    public function create()
    {
        $this->ensureCanCreateNotice();
        
        $classes = SchoolClass::all();
        return view('notices.create', compact('classes'));
    }
    
    /**
     * Store a newly created notice in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $this->ensureCanCreateNotice();
        
        $validated = $this->validateNoticeData($request);
        $validated['created_by'] = $user->id;
        
        $this->validateTeacherClassAccess($user, $validated);
        
        $notice = Notice::create($validated);
        
        return redirect()->route('notices.show', $notice)
            ->with('success', 'Notice created successfully.');
    }

    /**
     * Ensure user can create notices
     */
    private function ensureCanCreateNotice(): void
    {
        $user = Auth::user();
        
        if (!$user->isAdmin() && !$user->isTeacher()) {
            abort(403, 'You do not have permission to create notices.');
        }
    }

    /**
     * Validate notice data
     */
    private function validateNoticeData(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'notice_type' => 'required|string|in:general,exam,event,holiday,other',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'target_audience' => 'required|string|in:all,students,teachers,parents',
            'class_id' => 'nullable|integer|exists:school_classes,id',
            'status' => 'boolean',
        ]);
    }

    /**
     * Validate teacher access to class
     */
    private function validateTeacherClassAccess($user, array $validated): void
    {
        if ($user->isTeacher() && !$user->isAdmin() && isset($validated['class_id'])) {
            $hasAccess = $user->teacher->subjects()
                ->where('class_id', $validated['class_id'])
                ->exists();
                
            if (!$hasAccess) {
                abort(403, 'You do not have permission to create notices for this class.');
            }
        }
    }
    
    /**
     * Display the specified notice.
     */
    public function show(Notice $notice)
    {
        $user = Auth::user();
        
        if (!$this->userCanAccessNotice($user, $notice)) {
            return redirect()->route('notices.index')
                ->with('error', 'You do not have permission to view this notice.');
        }
        
        return view('notices.show', compact('notice'));
    }
    
    /**
     * Show the form for editing the specified notice.
     */
    public function edit(Notice $notice)
    {
        $this->ensureCanEditNotice($notice);
        
        $classes = SchoolClass::all();
        return view('notices.edit', compact('notice', 'classes'));
    }
    
    /**
     * Update the specified notice in storage.
     */
    public function update(Request $request, Notice $notice)
    {
        $this->ensureCanEditNotice($notice);
        
        $validated = $this->validateNoticeData($request);
        
        $notice->update($validated);
        
        return redirect()->route('notices.show', $notice)
            ->with('success', 'Notice updated successfully.');
    }
    
    /**
     * Remove the specified notice from storage.
     */
    public function destroy(Notice $notice)
    {
        $this->ensureCanEditNotice($notice);
        
        $notice->delete();
        
        return redirect()->route('notices.index')
            ->with('success', 'Notice deleted successfully.');
    }

    /**
     * Ensure user can edit the notice
     */
    private function ensureCanEditNotice(Notice $notice): void
    {
        $user = Auth::user();
        
        if ($notice->created_by !== $user->id && !$user->isAdmin()) {
            abort(403, 'You do not have permission to modify this notice.');
        }
    }
    
    /**
     * Check if a user can access a notice.
     */
    protected function userCanAccessNotice($user, $notice)
    {
        // Admins can access all notices
        if ($user->isAdmin()) {
            return true;
        }
        
        // Creator of the notice can access it
        if ($notice->created_by === $user->id) {
            return true;
        }
        
        // Check if notice is active
        if (!$notice->status) {
            return false;
        }
        
        // Check target audience
        $targetAudience = $notice->target_audience;
        
        if ($targetAudience === 'all') {
            return true;
        }
        
        if ($targetAudience === 'students' && $user->isStudent()) {
            // Check if notice is for specific class
            if ($notice->class_id) {
                return $user->student && $user->student->class_id === $notice->class_id;
            }
            return true;
        }
        
        if ($targetAudience === 'teachers' && $user->isTeacher()) {
            return true;
        }
        
        if ($targetAudience === 'parents' && $user->isParent()) {
            // If notice is for a specific class, check if parent has children in that class
            if ($notice->class_id && $user->parent) {
                return $user->parent->students()
                    ->where('class_id', $notice->class_id)
                    ->exists();
            }
            return true;
        }
        
        return false;
    }
}
