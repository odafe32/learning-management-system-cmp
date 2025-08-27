<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Course;
use App\Models\CourseStudent;
use Illuminate\Support\Facades\DB;

class CourseStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all students and courses
        $students = User::where('role', 'student')->get();
        $courses = Course::where('status', 'active')->get();

        if ($students->isEmpty() || $courses->isEmpty()) {
            $this->command->warn('No students or courses found. Please run UserSeeder and create some courses first.');
            return;
        }

        $enrollments = [];
        $statuses = ['active', 'inactive', 'completed'];

        // Create random enrollments
        foreach ($students as $student) {
            // Each student enrolls in 2-5 random courses
            $coursesToEnroll = $courses->random(rand(2, min(5, $courses->count())));
            
            foreach ($coursesToEnroll as $course) {
                // Check if enrollment already exists
                $exists = DB::table('course_student')
                    ->where('user_id', $student->id)
                    ->where('course_id', $course->id)
                    ->exists();

                if (!$exists) {
                    $enrollments[] = [
                        'user_id' => $student->id,
                        'course_id' => $course->id,
                        'enrolled_at' => now()->subDays(rand(1, 90)),
                        'status' => $statuses[array_rand($statuses)],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        // Insert enrollments in chunks for better performance
        if (!empty($enrollments)) {
            $chunks = array_chunk($enrollments, 100);
            foreach ($chunks as $chunk) {
                DB::table('course_student')->insert($chunk);
            }

            $this->command->info('Created ' . count($enrollments) . ' course enrollments.');
        } else {
            $this->command->warn('No new enrollments created.');
        }

        // Create some specific enrollments for testing
        $this->createTestEnrollments();
    }

    /**
     * Create specific test enrollments
     */
    private function createTestEnrollments(): void
    {
        // Get first instructor and their courses
        $instructor = User::where('role', 'instructor')->first();
        if (!$instructor) {
            return;
        }

        $instructorCourses = Course::where('user_id', $instructor->id)->limit(2)->get();
        if ($instructorCourses->isEmpty()) {
            return;
        }

        // Get some students for testing
        $testStudents = User::where('role', 'student')->limit(10)->get();
        
        foreach ($instructorCourses as $course) {
            foreach ($testStudents as $student) {
                // Check if enrollment already exists
                $exists = DB::table('course_student')
                    ->where('user_id', $student->id)
                    ->where('course_id', $course->id)
                    ->exists();

                if (!$exists) {
                    DB::table('course_student')->insert([
                        'user_id' => $student->id,
                        'course_id' => $course->id,
                        'enrolled_at' => now()->subDays(rand(1, 30)),
                        'status' => 'active',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        $this->command->info('Created test enrollments for instructor courses.');
    }
}