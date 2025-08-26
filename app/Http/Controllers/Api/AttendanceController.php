<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AttendanceController extends Controller
{
    /**
     * Display a listing of attendance records.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Attendance::with(['student.user', 'course', 'subject']);
        
        // Filter by date range
        if ($request->has('start_date')) {
            $query->where('date', '>=', $request->start_date);
        }
        
        if ($request->has('end_date')) {
            $query->where('date', '<=', $request->end_date);
        }
        
        // Filter by student
        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
        }
        
        // Filter by course
        if ($request->has('course_id')) {
            $query->where('course_id', $request->course_id);
        }
        
        $attendance = $query->paginate(15);
        
        return response()->json([
            'status' => 'success',
            'data' => $attendance
        ]);
    }

    /**
     * Store a newly created attendance record.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,excused',
            'notes' => 'nullable|string',
        ]);

        $attendance = Attendance::create($request->all());
        $attendance->load(['student.user', 'course', 'subject']);

        return response()->json([
            'status' => 'success',
            'message' => 'Attendance recorded successfully',
            'data' => $attendance
        ], 201);
    }

    /**
     * Display the specified attendance record.
     */
    public function show(Attendance $attendance): JsonResponse
    {
        $attendance->load(['student.user', 'course', 'subject']);
        
        return response()->json([
            'status' => 'success',
            'data' => $attendance
        ]);
    }

    /**
     * Update the specified attendance record.
     */
    public function update(Request $request, Attendance $attendance): JsonResponse
    {
        $request->validate([
            'status' => 'sometimes|in:present,absent,late,excused',
            'notes' => 'nullable|string',
        ]);

        $attendance->update($request->all());
        $attendance->load(['student.user', 'course', 'subject']);

        return response()->json([
            'status' => 'success',
            'message' => 'Attendance updated successfully',
            'data' => $attendance
        ]);
    }

    /**
     * Remove the specified attendance record.
     */
    public function destroy(Attendance $attendance): JsonResponse
    {
        $attendance->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Attendance record deleted successfully'
        ]);
    }

    /**
     * Get attendance summary for a student.
     */
    public function studentSummary(Request $request, $studentId): JsonResponse
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());
        
        $attendance = Attendance::where('student_id', $studentId)
            ->whereBetween('date', [$startDate, $endDate])
            ->with(['course', 'subject'])
            ->get();
            
        $summary = [
            'total_days' => $attendance->count(),
            'present' => $attendance->where('status', 'present')->count(),
            'absent' => $attendance->where('status', 'absent')->count(),
            'late' => $attendance->where('status', 'late')->count(),
            'excused' => $attendance->where('status', 'excused')->count(),
            'attendance_percentage' => $attendance->count() > 0 
                ? round(($attendance->whereIn('status', ['present', 'late'])->count() / $attendance->count()) * 100, 2)
                : 0,
            'records' => $attendance
        ];
        
        return response()->json([
            'status' => 'success',
            'data' => $summary
        ]);
    }

    /**
     * Record bulk attendance for a class.
     */
    public function bulkStore(Request $request): JsonResponse
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*.student_id' => 'required|exists:students,id',
            'attendance.*.status' => 'required|in:present,absent,late,excused',
            'attendance.*.notes' => 'nullable|string',
        ]);

        $attendanceRecords = [];
        
        foreach ($request->attendance as $record) {
            $attendanceData = array_merge($record, [
                'course_id' => $request->course_id,
                'subject_id' => $request->subject_id,
                'date' => $request->date,
            ]);
            
            $attendance = Attendance::updateOrCreate(
                [
                    'student_id' => $record['student_id'],
                    'course_id' => $request->course_id,
                    'date' => $request->date,
                ],
                $attendanceData
            );
            
            $attendanceRecords[] = $attendance->load(['student.user', 'course', 'subject']);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Bulk attendance recorded successfully',
            'data' => $attendanceRecords
        ], 201);
    }
}
