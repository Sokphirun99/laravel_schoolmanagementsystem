<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:portal');
    }

    /**
     * Display a listing of announcements.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::guard('portal')->user();
        $userType = $user->user_type; // 'student' or 'parent'
        
        // Get announcements for this user type or for all users
        $announcements = Announcement::where('status', true)
            ->where(function ($query) use ($userType) {
                $query->where('audience', $userType)
                    ->orWhere('audience', 'all');
            })
            ->whereNull('expiry_date')
            ->orWhere('expiry_date', '>=', now())
            ->latest()
            ->paginate(10);
            
        return view('portal.announcements.index', compact('announcements'));
    }

    /**
     * Display the specified announcement.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function show(Announcement $announcement)
    {
        $user = Auth::guard('portal')->user();
        $userType = $user->user_type;
        
        // Check if the user should be able to view this announcement
        if (!in_array($announcement->audience, [$userType, 'all'])) {
            abort(403, 'Unauthorized access to announcement');
        }
        
        return view('portal.announcements.show', compact('announcement'));
    }
}
