<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class StudentController extends Controller
{
    /**
     * Display a listing of students.
     */
    public function index(): JsonResponse
    {
        $students = Student::with(['user', 'schoolClass', 'section'])->paginate(15);
        
        return response()->json([
            'status' => 'success',
            'data' => $students
        ]);
    }

    /**
     * Store a newly created student.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'school_class_id' => 'required|exists:school_classes,id',
            'section_id' => 'required|exists:sections,id',
            'roll_number' => 'required|string|max:50',
            'admission_date' => 'required|date',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:20',
        ]);

        $student = Student::create($request->all());
        $student->load(['user', 'schoolClass', 'section']);

        return response()->json([
            'status' => 'success',
            'message' => 'Student created successfully',
            'data' => $student
        ], 201);
    }

    /**
     * Display the specified student.
     */
    public function show(Student $student): JsonResponse
    {
        $student->load(['user', 'schoolClass', 'section', 'courseEnrollments.course']);
        
        return response()->json([
            'status' => 'success',
            'data' => $student
        ]);
    }

    /**
     * Update the specified student.
     */
    public function update(Request $request, Student $student): JsonResponse
    {
        $request->validate([
            'school_class_id' => 'sometimes|exists:school_classes,id',
            'section_id' => 'sometimes|exists:sections,id',
            'roll_number' => 'sometimes|string|max:50',
            'admission_date' => 'sometimes|date',
            'date_of_birth' => 'sometimes|date',
            'gender' => 'sometimes|in:male,female,other',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:20',
        ]);

        $student->update($request->all());
        $student->load(['user', 'schoolClass', 'section']);

        return response()->json([
            'status' => 'success',
            'message' => 'Student updated successfully',
            'data' => $student
        ]);
    }

    /**
     * Remove the specified student.
     */
    public function destroy(Student $student): JsonResponse
    {
        $student->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Student deleted successfully'
        ]);
    }

    /**
     * Get student's grades.
     */
    public function grades(Student $student): JsonResponse
    {
        $grades = $student->grades()->with(['assignment.course', 'assignment.subject'])->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $grades
        ]);
    }

    /**
     * Get student's attendance.
     */
    public function attendance(Student $student): JsonResponse
    {
        $attendance = $student->attendances()->with(['course', 'subject'])->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $attendance
        ]);
    }
}
