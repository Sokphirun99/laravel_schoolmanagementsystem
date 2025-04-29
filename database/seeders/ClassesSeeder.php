<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Faker\Factory as Faker;

class ClassesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // Add subject-specific descriptions
        $subjectDescriptions = [
            'Mathematics' => [
                'elementary' => "Foundational mathematics course covering number sense, basic operations, measurement, and introductory geometry. Students develop problem-solving skills through hands-on activities and practical applications.",
                'middle' => "Intermediate mathematics curriculum focusing on pre-algebra concepts, proportional reasoning, geometry, and data analysis. Students engage in collaborative problem-solving and mathematical modeling.",
                'high' => "Advanced mathematics course covering algebraic concepts, functions, trigonometry, and calculus foundations. Students develop analytical thinking and apply mathematics to real-world scenarios."
            ],
            'English Language' => [
                'elementary' => "Foundational literacy program focusing on phonics, vocabulary building, reading comprehension, and basic writing skills. Students develop communication abilities through guided reading and creative expression.",
                'middle' => "Intermediate English language arts curriculum developing critical reading strategies, grammar mastery, and structured writing. Students analyze various text types and practice effective written and oral communication.",
                'high' => "Advanced English course focusing on literature analysis, rhetorical techniques, and sophisticated composition. Students engage with diverse texts while developing college-level critical thinking and writing skills."
            ],
            'Science' => [
                'elementary' => "Introductory science course exploring natural phenomena through observation, experimentation, and inquiry. Students investigate basic concepts in life, earth, and physical sciences through hands-on activities.",
                'middle' => "Comprehensive science curriculum covering key concepts in biology, chemistry, physics, and earth sciences. Students develop scientific inquiry skills through laboratory investigations and research projects.",
                'high' => "Advanced science course providing in-depth exploration of scientific principles and research methodologies. Students conduct sophisticated experiments and analyze complex scientific phenomena."
            ],
            'Social Studies' => [
                'elementary' => "Foundational social studies program introducing communities, cultures, geography, and history. Students develop civic awareness through engaging activities and cross-cultural explorations.",
                'middle' => "Intermediate social studies curriculum examining world cultures, historical developments, and civic systems. Students analyze primary sources and develop critical thinking about social phenomena.",
                'high' => "Advanced social studies course exploring complex historical events, political systems, and global interconnections. Students engage in research, debate, and analysis of historical and contemporary issues."
            ],
            'Physics' => [
                'middle' => "Introductory physics course covering motion, forces, energy, and basic mechanics. Students develop scientific reasoning through hands-on experiments and mathematical modeling.",
                'high' => "Comprehensive physics curriculum exploring mechanics, electricity, magnetism, waves, and modern physics. Students conduct sophisticated laboratory investigations and apply mathematical analysis to physical phenomena."
            ],
            'Chemistry' => [
                'middle' => "Foundational chemistry course introducing atomic structure, chemical reactions, and basic laboratory techniques. Students explore matter and its transformations through experiments and models.",
                'high' => "Advanced chemistry curriculum covering atomic theory, chemical bonding, stoichiometry, and thermodynamics. Students develop laboratory skills and analytical thinking through complex chemical investigations."
            ],
            'Computer Science' => [
                'elementary' => "Introductory computing course developing digital literacy, basic coding concepts, and computational thinking. Students explore technology tools through creative problem-solving activities.",
                'middle' => "Intermediate computer science curriculum covering programming fundamentals, algorithm development, and digital applications. Students create computational solutions to real-world problems.",
                'high' => "Advanced computer science course exploring programming languages, data structures, and software development principles. Students design and implement complex computational solutions and applications."
            ]
        ];

        // Default description for subjects not specifically covered
        $defaultDescriptions = [
            'elementary' => "Foundational course developing essential skills and knowledge in this subject area. Students engage in interactive learning activities designed for elementary-level understanding.",
            'middle' => "Intermediate curriculum building core competencies and critical thinking in this subject area. Students participate in collaborative projects and skill development appropriate for middle school.",
            'high' => "Advanced course providing in-depth exploration of concepts and applications in this subject area. Students engage in sophisticated analysis and projects preparing them for higher education."
        ];

        // Clear existing class records if requested
        if ($this->command->confirm('Do you want to clear existing class records?', false)) {
            $this->command->info('Clearing existing class records...');
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('classes')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $this->command->info('Existing class records cleared.');
        }

        // Get school ID
        $schoolId = null;
        if (Schema::hasTable('schools')) {
            $school = DB::table('schools')->first();
            if ($school) {
                $schoolId = $school->id;
                $this->command->info("Using school ID: {$schoolId}");
            } else {
                $this->command->warn('No school found. Classes will have null school_id.');
            }
        }

        // Get available teachers
        $teachers = [];
        if (Schema::hasTable('teachers')) {
            $teachers = DB::table('teachers')->where('status', 'active')->pluck('id')->toArray();
        }

        if (empty($teachers)) {
            $this->command->warn('No teachers found. Classes will have null teacher_id.');
        } else {
            $this->command->info("Found " . count($teachers) . " teachers to assign to classes.");
        }

        // Check which columns actually exist in the classes table
        $classColumns = Schema::getColumnListing('classes');
        $this->command->info('Available class columns: ' . implode(', ', $classColumns));

        // Class subjects
        $subjects = [
            'Mathematics', 'English Language', 'Science', 'Social Studies', 'Physics',
            'Chemistry', 'Biology', 'History', 'Geography', 'Computer Science',
            'Physical Education', 'Art', 'Music', 'Economics', 'Business Studies',
            'Accounting', 'Foreign Languages', 'Religious Studies', 'Literature', 'Civics',
            'Environmental Science', 'Psychology', 'Sociology', 'Political Science', 'Philosophy'
        ];

        // Grade levels
        $gradeLevels = [
            'Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5',
            'Grade 6', 'Grade 7', 'Grade 8', 'Grade 9', 'Grade 10',
            'Grade 11', 'Grade 12'
        ];

        DB::beginTransaction();

        try {
            $this->command->info('Creating 25 class records...');
            $this->command->getOutput()->progressStart(25);

            for ($i = 0; $i < 25; $i++) {
                $classCode = 'CLS' . str_pad($i + 1, 3, '0', STR_PAD_LEFT);
                $subject = $subjects[$i % count($subjects)];
                $gradeLevel = $faker->randomElement($gradeLevels);

                $classData = [
                    'name' => $subject . ' - ' . $gradeLevel,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Add fields that exist in the table
                if (in_array('code', $classColumns)) {
                    $classData['code'] = $classCode;
                }

                if (in_array('description', $classColumns)) {
                    // Determine grade level category
                    $gradeNum = (int) filter_var($gradeLevel, FILTER_SANITIZE_NUMBER_INT);

                    if ($gradeNum >= 1 && $gradeNum <= 5) {
                        $level = 'elementary';
                    } elseif ($gradeNum >= 6 && $gradeNum <= 8) {
                        $level = 'middle';
                    } else {
                        $level = 'high';
                    }

                    // Get appropriate description based on subject and level
                    if (isset($subjectDescriptions[$subject]) && isset($subjectDescriptions[$subject][$level])) {
                        $classData['description'] = $subjectDescriptions[$subject][$level];
                    } else {
                        // Use default description if subject-specific one isn't available
                        $classData['description'] = $defaultDescriptions[$level];
                    }
                }

                if (in_array('grade_level', $classColumns)) {
                    $classData['grade_level'] = $gradeLevel;
                }

                if (in_array('subject', $classColumns)) {
                    $classData['subject'] = $subject;
                }

                if (in_array('room_number', $classColumns)) {
                    $classData['room_number'] = 'R' . $faker->numberBetween(100, 300);
                }

                if (in_array('capacity', $classColumns)) {
                    $classData['capacity'] = $faker->numberBetween(25, 40);
                }

                if (in_array('teacher_id', $classColumns) && !empty($teachers)) {
                    // Assign teacher - either sequentially or randomly
                    $classData['teacher_id'] = $teachers[$i % count($teachers)];
                }

                if (in_array('school_id', $classColumns)) {
                    $classData['school_id'] = $schoolId;
                }

                if (in_array('status', $classColumns)) {
                    $classData['status'] = $faker->randomElement(['active', 'inactive']);
                }

                if (in_array('academic_year', $classColumns)) {
                    $classData['academic_year'] = '2025-2026';
                }

                if (in_array('start_time', $classColumns)) {
                    $classData['start_time'] = $faker->dateTimeBetween('08:00:00', '14:00:00')->format('H:i:s');
                }

                if (in_array('end_time', $classColumns)) {
                    $classData['end_time'] = $faker->dateTimeBetween('09:00:00', '15:00:00')->format('H:i:s');
                }

                if (in_array('days', $classColumns)) {
                    $days = $faker->randomElements(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'], $faker->numberBetween(1, 5));
                    $classData['days'] = implode(',', $days);
                }

                // Insert the class record
                DB::table('classes')->insert($classData);

                $this->command->getOutput()->progressAdvance();
            }

            DB::commit();
            $this->command->getOutput()->progressFinish();
            $this->command->info('25 class records created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error creating class records: ' . $e->getMessage());
        }
    }
}
