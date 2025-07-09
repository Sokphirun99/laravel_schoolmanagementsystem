<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Timetable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TimetableController extends Controller
{
    /**
     * Display a listing of timetable entries.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Timetable::with(['course', 'subject', 'teacher', 'schoolClass', 'section']);
        
        // Filter by class
        if ($request->has('school_class_id')) {
            $query->where('school_class_id', $request->school_class_id);
        }
        
        // Filter by section
        if ($request->has('section_id')) {
            $query->where('section_id', $request->section_id);
        }
        
        // Filter by day of week
        if ($request->has('day_of_week')) {
            $query->where('day_of_week', $request->day_of_week);
        }
        
        $timetable = $query->orderBy('day_of_week')->orderBy('start_time')->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $timetable
        ]);
    }

    /**
     * Store a newly created timetable entry.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'school_class_id' => 'required|exists:school_classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'nullable|string|max:50',
        ]);

        $timetable = Timetable::create($request->all());
        $timetable->load(['course', 'subject', 'teacher', 'schoolClass', 'section']);

        return response()->json([
            'status' => 'success',
            'message' => 'Timetable entry created successfully',
            'data' => $timetable
        ], 201);
    }

    /**
     * Display the specified timetable entry.
     */
    public function show(Timetable $timetable): JsonResponse
    {
        $timetable->load(['course', 'subject', 'teacher', 'schoolClass', 'section']);
        
        return response()->json([
            'status' => 'success',
            'data' => $timetable
        ]);
    }

    /**
     * Update the specified timetable entry.
     */
    public function update(Request $request, Timetable $timetable): JsonResponse
    {
        $request->validate([
            'course_id' => 'sometimes|exists:courses,id',
            'subject_id' => 'sometimes|exists:subjects,id',
            'teacher_id' => 'sometimes|exists:teachers,id',
            'school_class_id' => 'sometimes|exists:school_classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'day_of_week' => 'sometimes|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'sometimes|date_format:H:i',
            'end_time' => 'sometimes|date_format:H:i|after:start_time',
            'room' => 'nullable|string|max:50',
        ]);

        $timetable->update($request->all());
        $timetable->load(['course', 'subject', 'teacher', 'schoolClass', 'section']);

        return response()->json([
            'status' => 'success',
            'message' => 'Timetable entry updated successfully',
            'data' => $timetable
        ]);
    }

    /**
     * Remove the specified timetable entry.
     */
    public function destroy(Timetable $timetable): JsonResponse
    {
        $timetable->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Timetable entry deleted successfully'
        ]);
    }

    /**
     * Get timetable for a specific teacher.
     */
    public function teacherTimetable($teacherId): JsonResponse
    {
        $timetable = Timetable::where('teacher_id', $teacherId)
            ->with(['course', 'subject', 'schoolClass', 'section'])
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_of_week');
        
        return response()->json([
            'status' => 'success',
            'data' => $timetable
        ]);
    }

    /**
     * Get timetable for a specific class and section.
     */
    public function classTimetable(Request $request): JsonResponse
    {
        $request->validate([
            'school_class_id' => 'required|exists:school_classes,id',
            'section_id' => 'nullable|exists:sections,id',
        ]);

        $query = Timetable::where('school_class_id', $request->school_class_id)
            ->with(['course', 'subject', 'teacher', 'schoolClass', 'section']);

        if ($request->section_id) {
            $query->where('section_id', $request->section_id);
        }

        $timetable = $query->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_of_week');
        
        return response()->json([
            'status' => 'success',
            'data' => $timetable
        ]);
    }
}
