<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Show attendance history for a student
     */
    public function history($studentId = null)
    {
        $user = Auth::guard('portal')->user();
        
        // For parents, get specific student
        if ($user->user_type === 'parent' && $studentId) {
            $student = Student::findOrFail($studentId);
            // Verify parent has access to this student
            if (!$user->parent->students->contains($student)) {
                abort(403, 'Unauthorized access');
            }
        } else {
            $student = $user->student;
        }
        
        $attendances = Attendance::where('student_id', $student->id)
            ->orderBy('date', 'desc')
            ->paginate(20);
            
        return view('portal.attendance.history', compact('student', 'attendances'));
    }
    
    /**
     * Show attendance summary for a student
     */
    public function summary($studentId = null)
    {
        $user = Auth::guard('portal')->user();
        
        // For parents, get specific student
        if ($user->user_type === 'parent' && $studentId) {
            $student = Student::findOrFail($studentId);
            // Verify parent has access to this student
            if (!$user->parent->students->contains($student)) {
                abort(403, 'Unauthorized access');
            }
        } else {
            $student = $user->student;
        }
        
        // Get current month's attendance
        $month = Carbon::now()->format('m');
        $year = Carbon::now()->format('Y');
        
        $attendances = Attendance::where('student_id', $student->id)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get();
            
        // Calculate stats
        $totalDays = $attendances->count();
        $present = $attendances->where('status', 'present')->count();
        $absent = $attendances->where('status', 'absent')->count();
        $late = $attendances->where('status', 'late')->count();
        
        $attendanceStats = [
            'total' => $totalDays,
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'present_percentage' => $totalDays > 0 ? round(($present / $totalDays) * 100) : 0,
            'month' => Carbon::now()->format('F Y')
        ];
        
        return view('portal.attendance.summary', compact('student', 'attendanceStats', 'attendances'));
    }
}
