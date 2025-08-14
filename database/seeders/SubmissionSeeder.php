<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Submission;
use App\Models\Assignment;
use App\Models\User;
use Carbon\Carbon;

class SubmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some assignments and students
        $assignments = Assignment::with('course')->take(5)->get();
        $students = User::where('role', 'student')->take(10)->get();

        if ($assignments->isEmpty()) {
            $this->command->info('No assignments found. Please run AssignmentSeeder first.');
            return;
        }

        if ($students->isEmpty()) {
            $this->command->info('No students found. Please run UserSeeder first.');
            return;
        }

        $this->command->info("Creating submissions for {$assignments->count()} assignments...");

        foreach ($assignments as $assignment) {
            // Create submissions for random students
            $selectedStudents = $students->random(rand(3, 7));
            
            $this->command->info("Creating submissions for assignment: {$assignment->title}");
            
            foreach ($selectedStudents as $student) {
                // Check if submission already exists
                $existingSubmission = Submission::where('assignment_id', $assignment->id)
                    ->where('student_id', $student->id)
                    ->first();
                
                if ($existingSubmission) {
                    continue; // Skip if submission already exists
                }

                $status = collect(['draft', 'submitted', 'pending', 'graded'])->random();
                $submittedAt = null;
                $gradedAt = null;
                $grade = null;
                $feedback = null;

                // Handle date logic properly
                $assignmentCreated = Carbon::parse($assignment->created_at);
                $assignmentDeadline = Carbon::parse($assignment->deadline);
                $now = Carbon::now();

                // Ensure we have valid date ranges
                $startDate = $assignmentCreated;
                $endDate = $assignmentDeadline->isBefore($now) ? $assignmentDeadline : $now;

                // If assignment was created in the future, use a reasonable past date
                if ($startDate->isFuture()) {
                    $startDate = $now->copy()->subDays(30);
                }

                // If end date is before start date, adjust it
                if ($endDate->isBefore($startDate)) {
                    $endDate = $startDate->copy()->addDays(7);
                }

                if (in_array($status, ['submitted', 'pending', 'graded'])) {
                    // Create submitted date between assignment creation and deadline (or now)
                    $submittedAt = fake()->dateTimeBetween($startDate, $endDate);
                    
                    // Convert to Carbon instance if it's not already
                    if (!$submittedAt instanceof Carbon) {
                        $submittedAt = Carbon::instance($submittedAt);
                    }
                }

                if ($status === 'graded') {
                    // Grade was given after submission
                    $gradeStartDate = $submittedAt ?? $startDate;
                    $gradeEndDate = $now;
                    
                    // Ensure grade date is after submission date
                    if ($gradeEndDate->isBefore($gradeStartDate)) {
                        $gradeEndDate = $gradeStartDate->copy()->addDays(1);
                    }
                    
                    $gradedAt = fake()->dateTimeBetween($gradeStartDate, $gradeEndDate);
                    
                    // Convert to Carbon instance if it's not already
                    if (!$gradedAt instanceof Carbon) {
                        $gradedAt = Carbon::instance($gradedAt);
                    }
                    
                    $grade = fake()->randomFloat(2, 60, 100);
                    $feedback = fake()->paragraph();
                }

                try {
                    Submission::create([
                        'assignment_id' => $assignment->id,
                        'student_id' => $student->id,
                        'code_content' => $this->generateSampleCode(),
                        'status' => $status,
                        'grade' => $grade,
                        'feedback' => $feedback,
                        'submitted_at' => $submittedAt,
                        'graded_at' => $gradedAt,
                    ]);
                    
                    $this->command->info("  ✓ Created {$status} submission for student: {$student->name}");
                    
                } catch (\Exception $e) {
                    $this->command->error("  ✗ Failed to create submission for student {$student->name}: " . $e->getMessage());
                }
            }
        }

        $totalSubmissions = Submission::count();
        $this->command->info("Submissions seeded successfully! Total submissions: {$totalSubmissions}");
    }

    private function generateSampleCode(): string
    {
        $samples = [
            '// JavaScript Solution
function fibonacci(n) {
    if (n <= 1) return n;
    return fibonacci(n - 1) + fibonacci(n - 2);
}

console.log(fibonacci(10));',

            '# Python Solution
def bubble_sort(arr):
    n = len(arr)
    for i in range(n):
        for j in range(0, n - i - 1):
            if arr[j] > arr[j + 1]:
                arr[j], arr[j + 1] = arr[j + 1], arr[j]
    return arr

numbers = [64, 34, 25, 12, 22, 11, 90]
print(bubble_sort(numbers))',

            '// Java Solution
public class Calculator {
    public static int add(int a, int b) {
        return a + b;
    }
    
    public static void main(String[] args) {
        System.out.println(add(5, 3));
    }
}',

            '<?php
// PHP Solution
class User {
    private $name;
    private $email;
    
    public function __construct($name, $email) {
        $this->name = $name;
        $this->email = $email;
    }
    
    public function getName() {
        return $this->name;
    }
}

$user = new User("John Doe", "john@example.com");
echo $user->getName();
?>',

            '// C++ Solution
#include <iostream>
#include <vector>
using namespace std;

int main() {
    vector<int> numbers = {1, 2, 3, 4, 5};
    int sum = 0;
    
    for(int num : numbers) {
        sum += num;
    }
    
    cout << "Sum: " << sum << endl;
    return 0;
}',

            '/* SQL Solution */
SELECT 
    students.name,
    courses.title,
    assignments.title as assignment_title,
    submissions.grade
FROM students
JOIN submissions ON students.id = submissions.student_id
JOIN assignments ON submissions.assignment_id = assignments.id
JOIN courses ON assignments.course_id = courses.id
WHERE submissions.status = "graded"
ORDER BY submissions.grade DESC;',

            '// React Component
import React, { useState } from "react";

function Counter() {
    const [count, setCount] = useState(0);
    
    return (
        <div>
            <h2>Count: {count}</h2>
            <button onClick={() => setCount(count + 1)}>
                Increment
            </button>
            <button onClick={() => setCount(count - 1)}>
                Decrement
            </button>
        </div>
    );
}

export default Counter;',

            '# Python Data Analysis
import pandas as pd
import numpy as np

# Create sample data
data = {
    "name": ["Alice", "Bob", "Charlie", "Diana"],
    "age": [25, 30, 35, 28],
    "score": [85, 92, 78, 96]
}

df = pd.DataFrame(data)
print("Average score:", df["score"].mean())
print("Top performer:", df.loc[df["score"].idxmax(), "name"])',
        ];

        return fake()->randomElement($samples);
    }
}