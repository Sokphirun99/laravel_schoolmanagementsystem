<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Teacher;

class DashboardService
{
    public function getStudentWidgetData(): array
    {
        $totalCount = Student::count();
        $activeCount = Student::where('status', 1)->count();

        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        $newThisMonth = Student::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

        $recentStudents = Student::with('user')->latest()->take(5)->get();

        return [
            'totalCount' => $totalCount,
            'activeCount' => $activeCount,
            'newThisMonth' => $newThisMonth,
            'recentStudents' => $recentStudents,
        ];
    }

    public function getTeacherWidgetData(): array
    {
        $totalCount = Teacher::count();
        $subjectCount = Teacher::select('subject_id')->distinct()->count();
        $classTeacherCount = Teacher::where('is_class_teacher', 1)->count();
        $topTeachers = Teacher::with('user')->withCount('classTeacher')->orderBy('class_teacher_count', 'desc')->take(5)->get();

        return [
            'totalCount' => $totalCount,
            'subjectCount' => $subjectCount,
            'classTeacherCount' => $classTeacherCount,
            'topTeachers' => $topTeachers,
        ];
    }
}
