<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Student;
use App\Models\Assignment;
use App\Models\Grade;
use Illuminate\Support\Collection;

class GradeCalculator
{
    /**
     * Calculate weighted average grade for a student in a course
     *
     * @param Student $student
     * @param Course $course
     * @return array
     */
    public function calculateCourseGrade(Student $student, Course $course): array
    {
        $assignments = Assignment::where('course_id', $course->id)->get();
        $totalWeightedScore = 0;
        $totalWeight = 0;
        $assignmentScores = [];
        
        foreach ($assignments as $assignment) {
            $grade = Grade::where('assignment_id', $assignment->id)
                          ->where('student_id', $student->id)
                          ->first();
            
            if ($grade) {
                $weightedScore = ($grade->points_earned / $assignment->max_points) * $assignment->weight;
                $totalWeightedScore += $weightedScore;
                $totalWeight += $assignment->weight;
                
                $assignmentScores[] = [
                    'assignment' => $assignment,
                    'grade' => $grade,
                    'percentage' => round(($grade->points_earned / $assignment->max_points) * 100, 2),
                    'weighted_score' => $weightedScore
                ];
            }
        }
        
        $finalScore = $totalWeight > 0 ? ($totalWeightedScore / $totalWeight) * 100 : 0;
        $letterGrade = $this->getLetterGrade($finalScore);
        
        return [
            'student' => $student,
            'course' => $course,
            'assignments' => $assignmentScores,
            'final_score' => round($finalScore, 2),
            'letter_grade' => $letterGrade,
        ];
    }
    
    /**
     * Get letter grade based on percentage
     *
     * @param float $percentage
     * @return string
     */
    public function getLetterGrade(float $percentage): string
    {
        if ($percentage >= 90) return 'A';
        if ($percentage >= 80) return 'B';
        if ($percentage >= 70) return 'C';
        if ($percentage >= 60) return 'D';
        return 'F';
    }
    
    /**
     * Calculate course summary statistics
     * 
     * @param Course $course
     * @return array
     */
    public function calculateCourseSummary(Course $course): array
    {
        $students = $course->students;
        $studentGrades = [];
        $totalScores = [];
        
        foreach ($students as $student) {
            $gradeInfo = $this->calculateCourseGrade($student, $course);
            $studentGrades[] = $gradeInfo;
            $totalScores[] = $gradeInfo['final_score'];
        }
        
        $avgScore = count($totalScores) > 0 ? array_sum($totalScores) / count($totalScores) : 0;
        $highestScore = count($totalScores) > 0 ? max($totalScores) : 0;
        $lowestScore = count($totalScores) > 0 ? min($totalScores) : 0;
        
        // Calculate grade distribution
        $distribution = [
            'A' => 0,
            'B' => 0,
            'C' => 0,
            'D' => 0,
            'F' => 0,
        ];
        
        foreach ($studentGrades as $grade) {
            $distribution[$grade['letter_grade']]++;
        }
        
        return [
            'course' => $course,
            'student_count' => count($students),
            'average_score' => round($avgScore, 2),
            'highest_score' => round($highestScore, 2),
            'lowest_score' => round($lowestScore, 2),
            'grade_distribution' => $distribution
        ];
    }
}
