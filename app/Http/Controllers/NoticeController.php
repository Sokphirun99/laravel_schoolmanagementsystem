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
        $query = Notice::query();
        
        // Filter by audience based on user role
        if ($user->isStudent()) {
            $query->forAudience('student');
            if ($user->student && $user->student->class_id) {
                $query->byClass($user->student->class_id);
            }
        } elseif ($user->isTeacher()) {
            $query->forAudience('teacher');
        } elseif ($user->isParent()) {
            $query->forAudience('parent');
        }
        
        // Allow admins and teachers to filter notices
        if ($user->isAdmin() || $user->isTeacher()) {
            if ($request->has('audience')) {
                $query->where('target_audience', $request->audience);
            }
            
            if ($request->has('class_id') && $request->class_id > 0) {
                $query->where('class_id', $request->class_id);
            }
        }
        
        // Always filter for active notices unless explicitly showing all
        if (!$request->has('show_all') || !$user->isAdmin()) {
            $query->active();
        }
        
        $notices = $query->orderBy('created_at', 'desc')->paginate(10);
        $classes = SchoolClass::all();
        
        return view('notices.index', compact('notices', 'classes'));
    }
    
    /**
     * Show the form for creating a new notice.
     */
    public function create()
    {
        // Only admins and teachers can create notices
        $user = Auth::user();
        
        if (!$user->isAdmin() && !$user->isTeacher()) {
            return redirect()->route('notices.index')
                ->with('error', 'You do not have permission to create notices.');
        }
        
        $classes = SchoolClass::all();
        return view('notices.create', compact('classes'));
    }
    
    /**
     * Store a newly created notice in storage.
     */
    public function store(Request $request)
    {
        // Only admins and teachers can create notices
        $user = Auth::user();
        
        if (!$user->isAdmin() && !$user->isTeacher()) {
            return redirect()->route('notices.index')
                ->with('error', 'You do not have permission to create notices.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'notice_type' => 'required|string|in:general,exam,event,holiday,other',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'target_audience' => 'required|string|in:all,students,teachers,parents',
            'class_id' => 'nullable|integer|exists:school_classes,id',
            'status' => 'boolean',
        ]);
        
        // Set the creator of the notice
        $validated['created_by'] = $user->id;
        
        // If teacher and not admin, validate class access
        if ($user->isTeacher() && !$user->isAdmin() && isset($validated['class_id'])) {
            // Check if teacher teaches this class
            $hasAccess = $user->teacher->subjects()
                ->where('class_id', $validated['class_id'])
                ->exists();
                
            if (!$hasAccess) {
                return redirect()->route('notices.create')
                    ->withInput()
                    ->with('error', 'You do not have permission to create notices for this class.');
            }
        }
        
        $notice = Notice::create($validated);
        
        return redirect()->route('notices.show', $notice)
            ->with('success', 'Notice created successfully.');
    }
    
    /**
     * Display the specified notice.
     */
    public function show(Notice $notice)
    {
        $user = Auth::user();
        
        // Check if user has access to this notice
        $hasAccess = $this->userCanAccessNotice($user, $notice);
        
        if (!$hasAccess) {
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
        $user = Auth::user();
        
        // Only creator or admins can edit notices
        if ($notice->created_by !== $user->id && !$user->isAdmin()) {
            return redirect()->route('notices.index')
                ->with('error', 'You do not have permission to edit this notice.');
        }
        
        $classes = SchoolClass::all();
        return view('notices.edit', compact('notice', 'classes'));
    }
    
    /**
     * Update the specified notice in storage.
     */
    public function update(Request $request, Notice $notice)
    {
        $user = Auth::user();
        
        // Only creator or admins can update notices
        if ($notice->created_by !== $user->id && !$user->isAdmin()) {
            return redirect()->route('notices.index')
                ->with('error', 'You do not have permission to update this notice.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'notice_type' => 'required|string|in:general,exam,event,holiday,other',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'target_audience' => 'required|string|in:all,students,teachers,parents',
            'class_id' => 'nullable|integer|exists:school_classes,id',
            'status' => 'boolean',
        ]);
        
        $notice->update($validated);
        
        return redirect()->route('notices.show', $notice)
            ->with('success', 'Notice updated successfully.');
    }
    
    /**
     * Remove the specified notice from storage.
     */
    public function destroy(Notice $notice)
    {
        $user = Auth::user();
        
        // Only creator or admins can delete notices
        if ($notice->created_by !== $user->id && !$user->isAdmin()) {
            return redirect()->route('notices.index')
                ->with('error', 'You do not have permission to delete this notice.');
        }
        
        $notice->delete();
        
        return redirect()->route('notices.index')
            ->with('success', 'Notice deleted successfully.');
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
