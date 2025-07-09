<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of announcements.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Announcement::query();
        
        // Filter by target audience
        if ($request->has('target_audience')) {
            $query->where('target_audience', $request->target_audience);
        }
        
        // Filter by active status
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }
        
        $announcements = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return response()->json([
            'status' => 'success',
            'data' => $announcements
        ]);
    }

    /**
     * Store a newly created announcement.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'target_audience' => 'required|in:all,students,teachers,parents',
            'priority' => 'required|in:low,medium,high,urgent',
            'expires_at' => 'nullable|date|after:now',
            'is_active' => 'boolean',
        ]);

        $announcement = Announcement::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Announcement created successfully',
            'data' => $announcement
        ], 201);
    }

    /**
     * Display the specified announcement.
     */
    public function show(Announcement $announcement): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $announcement
        ]);
    }

    /**
     * Update the specified announcement.
     */
    public function update(Request $request, Announcement $announcement): JsonResponse
    {
        $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'target_audience' => 'sometimes|in:all,students,teachers,parents',
            'priority' => 'sometimes|in:low,medium,high,urgent',
            'expires_at' => 'nullable|date|after:now',
            'is_active' => 'boolean',
        ]);

        $announcement->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Announcement updated successfully',
            'data' => $announcement
        ]);
    }

    /**
     * Remove the specified announcement.
     */
    public function destroy(Announcement $announcement): JsonResponse
    {
        $announcement->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Announcement deleted successfully'
        ]);
    }

    /**
     * Get active announcements for a specific audience.
     */
    public function getActive(Request $request): JsonResponse
    {
        $targetAudience = $request->input('audience', 'all');
        
        $announcements = Announcement::where('is_active', true)
            ->where(function ($query) use ($targetAudience) {
                $query->where('target_audience', $targetAudience)
                      ->orWhere('target_audience', 'all');
            })
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $announcements
        ]);
    }
}
