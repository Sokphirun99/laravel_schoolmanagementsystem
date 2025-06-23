<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fee;
use App\Models\Student;
use Carbon\Carbon;

class FeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all students
        $students = Student::all();
        
        if ($students->isEmpty()) {
            $this->command->info('No students found. Please add students first.');
            return;
        }

        // Fee types
        $feeTypes = [
            'Tuition Fee',
            'Exam Fee',
            'Library Fee',
            'Transportation Fee',
            'Lab Fee',
            'Sports Fee',
        ];

        // Create fees for each student
        foreach ($students as $student) {
            // Random number of fees between 1-3
            $feesCount = rand(1, 3);
            
            for ($i = 0; $i < $feesCount; $i++) {
                $feeType = $feeTypes[array_rand($feeTypes)];
                $amount = rand(50, 500);
                
                // Randomize due date between now and 30 days from now
                $dueDate = Carbon::now()->addDays(rand(5, 30));
                
                // Randomly set some as paid
                $status = rand(0, 1) ? 'paid' : 'pending';
                $paymentDate = $status == 'paid' ? Carbon::now()->subDays(rand(1, 10)) : null;
                
                Fee::create([
                    'student_id' => $student->id,
                    'class_id' => $student->class_id,
                    'fee_type' => $feeType,
                    'amount' => $amount,
                    'due_date' => $dueDate,
                    'payment_date' => $paymentDate,
                    'payment_method' => $status == 'paid' ? ['Cash', 'Credit Card', 'Bank Transfer'][rand(0, 2)] : null,
                    'transaction_id' => $status == 'paid' ? 'TXN-' . strtoupper(substr(md5(rand()), 0, 8)) : null,
                    'status' => $status,
                    'remarks' => $status == 'paid' ? 'Payment received' : 'Payment pending',
                ]);
                
                // Create a few overdue fees
                if (rand(0, 5) === 0) {
                    Fee::create([
                        'student_id' => $student->id,
                        'class_id' => $student->class_id,
                        'fee_type' => $feeTypes[array_rand($feeTypes)],
                        'amount' => rand(50, 500),
                        'due_date' => Carbon::now()->subDays(rand(5, 30)),
                        'payment_date' => null,
                        'status' => 'overdue',
                        'remarks' => 'Payment overdue',
                    ]);
                }
            }
        }
        
        $this->command->info('Sample fees created successfully.');
    }
}
