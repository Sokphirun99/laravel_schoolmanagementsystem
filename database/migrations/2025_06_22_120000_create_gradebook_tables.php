<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradebookTables extends Migration
{
    public function up()
    {
        // Courses Table
        if (!Schema::hasTable('courses')) {
            Schema::create('courses', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->foreignId('teacher_id')->constrained('users');
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // Assignments Table
        if (!Schema::hasTable('assignments')) {
            Schema::create('assignments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('course_id')->constrained();
                $table->string('title');
                $table->text('description')->nullable();
                $table->decimal('max_points', 5, 2);
                $table->date('due_date');
                $table->string('assignment_type'); // homework, quiz, exam, project
                $table->decimal('weight', 5, 2)->default(1.00); // Weight for final grade
                $table->timestamps();
            });
        }

        // Grades Table
        if (!Schema::hasTable('grades')) {
            Schema::create('grades', function (Blueprint $table) {
                $table->id();
                $table->foreignId('assignment_id')->constrained();
                $table->foreignId('student_id')->constrained();
                $table->decimal('points_earned', 5, 2);
                $table->text('feedback')->nullable();
                $table->timestamps();
                
                $table->unique(['assignment_id', 'student_id']);
            });
        }

        // Course Enrollments Table
        if (!Schema::hasTable('course_enrollments')) {
            Schema::create('course_enrollments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('course_id')->constrained();
                $table->foreignId('student_id')->constrained();
                $table->string('semester');
                $table->timestamps();
                
                $table->unique(['course_id', 'student_id', 'semester']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('course_enrollments');
        Schema::dropIfExists('grades');
        Schema::dropIfExists('assignments');
        Schema::dropIfExists('courses');
    }
}
