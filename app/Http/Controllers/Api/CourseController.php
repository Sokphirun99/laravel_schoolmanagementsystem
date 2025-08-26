<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CourseController extends Controller
{
    /**
     * Display a listing of courses.
     */
    public function index(): JsonResponse
    {
        $courses = Course::with(['subject', 'teacher', 'schoolClass', 'section'])->paginate(15);
        
        return response()->json([
            'status' => 'success',
            'data' => $courses
        ]);
    }

    /**
     * Store a newly created course.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:courses',
            'description' => 'nullable|string',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'school_class_id' => 'required|exists:school_classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'credit_hours' => 'nullable|integer|min:1',
            'semester' => 'nullable|string|max:50',
            'academic_year' => 'required|string|max:20',
        ]);

        $course = Course::create($request->all());
        $course->load(['subject', 'teacher', 'schoolClass', 'section']);

        return response()->json([
            'status' => 'success',
            'message' => 'Course created successfully',
            'data' => $course
        ], 201);
    }

    /**
     * Display the specified course.
     */
    public function show(Course $course): JsonResponse
    {
        $course->load(['subject', 'teacher', 'schoolClass', 'section', 'enrollments.student.user']);
        
        return response()->json([
            'status' => 'success',
            'data' => $course
        ]);
    }

    /**
     * Update the specified course.
     */
    public function update(Request $request, Course $course): JsonResponse
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'code' => 'sometimes|string|max:50|unique:courses,code,' . $course->id,
            'description' => 'nullable|string',
            'subject_id' => 'sometimes|exists:subjects,id',
            'teacher_id' => 'sometimes|exists:teachers,id',
            'school_class_id' => 'sometimes|exists:school_classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'credit_hours' => 'nullable|integer|min:1',
            'semester' => 'nullable|string|max:50',
            'academic_year' => 'sometimes|string|max:20',
        ]);

        $course->update($request->all());
        $course->load(['subject', 'teacher', 'schoolClass', 'section']);

        return response()->json([
            'status' => 'success',
            'message' => 'Course updated successfully',
            'data' => $course
        ]);
    }

    /**
     * Remove the specified course.
     */
    public function destroy(Course $course): JsonResponse
    {
        $course->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Course deleted successfully'
        ]);
    }

    /**
     * Get course assignments.
     */
    public function assignments(Course $course): JsonResponse
    {
        $assignments = $course->assignments()->with(['subject'])->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $assignments
        ]);
    }

    /**
     * Get course enrolled students.
     */
    public function students(Course $course): JsonResponse
    {
        $students = $course->enrollments()->with('student.user')->get()->pluck('student');
        
        return response()->json([
            'status' => 'success',
            'data' => $students
        ]);
    }

    /**
     * Enroll a student in the course.
     */
    public function enrollStudent(Request $request, Course $course): JsonResponse
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);

        $enrollment = $course->enrollments()->create([
            'student_id' => $request->student_id,
            'enrollment_date' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Student enrolled successfully',
            'data' => $enrollment->load('student.user')
        ], 201);
    }
}
