<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:portal');
    }
    
    public function index()
    {
        $user = Auth::guard('portal')->user();
        $messages = Message::where('recipient_id', $user->id)
            ->orWhere('sender_id', $user->id)
            ->with('sender', 'recipient')
            ->latest()
            ->get()
            ->groupBy('conversation_id');
            
        return view('portal.communication.index', compact('messages'));
    }
    
    public function showConversation($conversationId)
    {
        $user = Auth::guard('portal')->user();
        
        $messages = Message::where('conversation_id', $conversationId)
            ->with('sender', 'recipient')
            ->get();
            
        // Check authorization
        $canAccess = $messages->filter(function($message) use ($user) {
            return $message->sender_id == $user->id || $message->recipient_id == $user->id;
        })->count() > 0;
        
        if (!$canAccess && $messages->count() > 0) {
            abort(403, 'Unauthorized access');
        }
            
        // Mark messages as read
        Message::where('conversation_id', $conversationId)
            ->where('recipient_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
            
        return view('portal.communication.conversation', compact('messages', 'conversationId'));
    }
    
    public function sendMessage(Request $request, $conversationId = null)
    {
        $user = Auth::guard('portal')->user();
        
        $request->validate([
            'recipient_id' => 'required_if:conversation_id,null|exists:users,id',
            'message' => 'required'
        ]);
        
        $recipientId = $conversationId ? 
            Message::where('conversation_id', $conversationId)
                ->where('sender_id', '!=', $user->id)
                ->value('sender_id') 
            : $request->recipient_id;
            
        $conversationId = $conversationId ?? uniqid();
        
        $messageContent = $request->message;
        
        // Add student information if provided by a parent
        if ($user->user_type === 'parent' && $request->has('student_id')) {
            $studentId = $request->student_id;
            $student = \App\Models\Student::find($studentId);
            if ($student) {
                $messageContent = "RE: Student " . $student->first_name . " " . $student->last_name . " (ID: " . $student->student_id . ")\n\n" . $messageContent;
            }
        }
        
        Message::create([
            'conversation_id' => $conversationId,
            'sender_id' => $user->id,
            'recipient_id' => $recipientId,
            'message' => $messageContent
        ]);
        
        return redirect()->route('portal.communication.conversation', $conversationId);
    }
    
    public function listTeachers(Request $request)
    {
        $teachers = User::whereHas('roles', function ($query) {
            $query->where('name', 'teacher');
        })->get();
        
        $studentId = $request->query('student');
        
        return view('portal.communication.teachers', compact('teachers', 'studentId'));
    }
}
