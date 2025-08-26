<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TeacherController extends Controller
{
    /**
     * Display a listing of teachers.
     */
    public function index(): JsonResponse
    {
        $teachers = Teacher::with(['user', 'subjects'])->paginate(15);
        
        return response()->json([
            'status' => 'success',
            'data' => $teachers
        ]);
    }

    /**
     * Store a newly created teacher.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'employee_id' => 'required|string|max:50|unique:teachers',
            'hire_date' => 'required|date',
            'qualification' => 'nullable|string',
            'experience_years' => 'nullable|integer|min:0',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:20',
        ]);

        $teacher = Teacher::create($request->all());
        $teacher->load(['user', 'subjects']);

        return response()->json([
            'status' => 'success',
            'message' => 'Teacher created successfully',
            'data' => $teacher
        ], 201);
    }

    /**
     * Display the specified teacher.
     */
    public function show(Teacher $teacher): JsonResponse
    {
        $teacher->load(['user', 'subjects', 'courses']);
        
        return response()->json([
            'status' => 'success',
            'data' => $teacher
        ]);
    }

    /**
     * Update the specified teacher.
     */
    public function update(Request $request, Teacher $teacher): JsonResponse
    {
        $request->validate([
            'employee_id' => 'sometimes|string|max:50|unique:teachers,employee_id,' . $teacher->id,
            'hire_date' => 'sometimes|date',
            'qualification' => 'nullable|string',
            'experience_years' => 'nullable|integer|min:0',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:20',
        ]);

        $teacher->update($request->all());
        $teacher->load(['user', 'subjects']);

        return response()->json([
            'status' => 'success',
            'message' => 'Teacher updated successfully',
            'data' => $teacher
        ]);
    }

    /**
     * Remove the specified teacher.
     */
    public function destroy(Teacher $teacher): JsonResponse
    {
        $teacher->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Teacher deleted successfully'
        ]);
    }

    /**
     * Get teacher's courses.
     */
    public function courses(Teacher $teacher): JsonResponse
    {
        $courses = $teacher->courses()->with(['subject', 'schoolClass', 'section'])->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $courses
        ]);
    }

    /**
     * Get teacher's students.
     */
    public function students(Teacher $teacher): JsonResponse
    {
        $students = collect();
        foreach ($teacher->courses as $course) {
            $courseStudents = $course->enrollments()->with('student.user')->get()->pluck('student');
            $students = $students->merge($courseStudents);
        }
        
        return response()->json([
            'status' => 'success',
            'data' => $students->unique('id')->values()
        ]);
    }
}
