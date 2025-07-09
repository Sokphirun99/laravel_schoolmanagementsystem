<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AssignmentController extends Controller
{
    /**
     * Display a listing of assignments.
     */
    public function index(): JsonResponse
    {
        $assignments = Assignment::with(['course', 'subject'])->paginate(15);
        
        return response()->json([
            'status' => 'success',
            'data' => $assignments
        ]);
    }

    /**
     * Store a newly created assignment.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
            'subject_id' => 'required|exists:subjects,id',
            'due_date' => 'required|date',
            'max_points' => 'required|numeric|min:0',
            'assignment_type' => 'required|in:homework,quiz,exam,project,lab',
            'instructions' => 'nullable|string',
        ]);

        $assignment = Assignment::create($request->all());
        $assignment->load(['course', 'subject']);

        return response()->json([
            'status' => 'success',
            'message' => 'Assignment created successfully',
            'data' => $assignment
        ], 201);
    }

    /**
     * Display the specified assignment.
     */
    public function show(Assignment $assignment): JsonResponse
    {
        $assignment->load(['course', 'subject', 'grades.student.user']);
        
        return response()->json([
            'status' => 'success',
            'data' => $assignment
        ]);
    }

    /**
     * Update the specified assignment.
     */
    public function update(Request $request, Assignment $assignment): JsonResponse
    {
        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'sometimes|exists:courses,id',
            'subject_id' => 'sometimes|exists:subjects,id',
            'due_date' => 'sometimes|date',
            'max_points' => 'sometimes|numeric|min:0',
            'assignment_type' => 'sometimes|in:homework,quiz,exam,project,lab',
            'instructions' => 'nullable|string',
        ]);

        $assignment->update($request->all());
        $assignment->load(['course', 'subject']);

        return response()->json([
            'status' => 'success',
            'message' => 'Assignment updated successfully',
            'data' => $assignment
        ]);
    }

    /**
     * Remove the specified assignment.
     */
    public function destroy(Assignment $assignment): JsonResponse
    {
        $assignment->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Assignment deleted successfully'
        ]);
    }

    /**
     * Get assignment grades.
     */
    public function grades(Assignment $assignment): JsonResponse
    {
        $grades = $assignment->grades()->with('student.user')->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $grades
        ]);
    }

    /**
     * Submit a grade for the assignment.
     */
    public function submitGrade(Request $request, Assignment $assignment): JsonResponse
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'points_earned' => 'required|numeric|min:0|max:' . $assignment->max_points,
            'feedback' => 'nullable|string',
        ]);

        $grade = Grade::updateOrCreate(
            [
                'assignment_id' => $assignment->id,
                'student_id' => $request->student_id,
            ],
            [
                'points_earned' => $request->points_earned,
                'feedback' => $request->feedback,
                'graded_at' => now(),
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Grade submitted successfully',
            'data' => $grade->load('student.user')
        ]);
    }
}
