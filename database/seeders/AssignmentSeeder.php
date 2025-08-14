<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\User;
use Carbon\Carbon;

class AssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get instructors and courses
        $instructors = User::where('role', 'instructor')->get();
        $courses = Course::all();

        if ($instructors->isEmpty()) {
            $this->command->info('No instructors found. Please run UserSeeder first.');
            return;
        }

        if ($courses->isEmpty()) {
            $this->command->info('No courses found. Please run CourseSeeder first.');
            return;
        }

        $this->command->info("Creating assignments for {$courses->count()} courses...");

        $assignmentTemplates = [
            [
                'title' => 'Basic Calculator Implementation',
                'description' => 'Create a simple calculator that can perform basic arithmetic operations (addition, subtraction, multiplication, division).',
                'code_sample' => '// JavaScript Calculator Template
function calculator(operation, num1, num2) {
    switch(operation) {
        case "add":
            return num1 + num2;
        case "subtract":
            return num1 - num2;
        case "multiply":
            return num1 * num2;
        case "divide":
            return num2 !== 0 ? num1 / num2 : "Error: Division by zero";
        default:
            return "Error: Invalid operation";
    }
}

// Test your calculator
console.log(calculator("add", 5, 3));'
            ],
            [
                'title' => 'Array Sorting Algorithm',
                'description' => 'Implement a sorting algorithm (bubble sort, selection sort, or insertion sort) to sort an array of numbers.',
                'code_sample' => '# Python Sorting Template
def bubble_sort(arr):
    n = len(arr)
    # Implement bubble sort algorithm here
    for i in range(n):
        for j in range(0, n - i - 1):
            # Compare adjacent elements and swap if needed
            pass
    return arr

# Test your sorting function
numbers = [64, 34, 25, 12, 22, 11, 90]
sorted_numbers = bubble_sort(numbers.copy())
print("Original:", numbers)
print("Sorted:", sorted_numbers)'
            ],
            [
                'title' => 'Database Query Exercise',
                'description' => 'Write SQL queries to retrieve and manipulate data from a student database.',
                'code_sample' => '-- SQL Query Template
-- 1. Select all students with their courses
SELECT students.name, courses.title 
FROM students 
JOIN enrollments ON students.id = enrollments.student_id
JOIN courses ON enrollments.course_id = courses.id;

-- 2. Find students with grades above 85
-- Write your query here

-- 3. Count students per course
-- Write your query here'
            ],
            [
                'title' => 'Object-Oriented Programming',
                'description' => 'Create a class hierarchy for a library management system with books, authors, and borrowers.',
                'code_sample' => '// Java OOP Template
public class Book {
    private String title;
    private String author;
    private boolean isAvailable;
    
    public Book(String title, String author) {
        this.title = title;
        this.author = author;
        this.isAvailable = true;
    }
    
    // Implement getter and setter methods
    // Implement borrow() and return() methods
}

public class Library {
    // Implement library management methods
}'
            ],
            [
                'title' => 'Web API Development',
                'description' => 'Create a RESTful API endpoint for managing user data with proper HTTP methods and status codes.',
                'code_sample' => '<?php
// PHP API Template
class UserController {
    
    public function index() {
        // GET /users - Return all users
        // Implement here
    }
    
    public function show($id) {
        // GET /users/{id} - Return specific user
        // Implement here
    }
    
    public function store($request) {
        // POST /users - Create new user
        // Implement here
    }
    
    public function update($id, $request) {
        // PUT /users/{id} - Update user
        // Implement here
    }
    
    public function destroy($id) {
        // DELETE /users/{id} - Delete user
        // Implement here
    }
}
?>'
            ],
            [
                'title' => 'Data Structures Implementation',
                'description' => 'Implement a stack or queue data structure with all basic operations.',
                'code_sample' => '// C++ Stack Template
#include <iostream>
#include <vector>

class Stack {
private:
    std::vector<int> data;
    
public:
    void push(int value) {
        // Implement push operation
    }
    
    int pop() {
        // Implement pop operation
    }
    
    int top() {
        // Implement top operation
    }
    
    bool isEmpty() {
        // Implement isEmpty check
    }
    
    int size() {
        // Implement size method
    }
};'
            ]
        ];

        foreach ($courses as $course) {
            // Create 2-4 assignments per course
            $assignmentCount = rand(2, 4);
            
            for ($i = 0; $i < $assignmentCount; $i++) {
                $template = fake()->randomElement($assignmentTemplates);
                
                // Create assignment with dates that make sense
                $createdAt = fake()->dateTimeBetween('-2 months', '-1 week');
                $deadline = fake()->dateTimeBetween($createdAt, '+1 month');
                
                // Convert to Carbon instances
                $createdAt = Carbon::instance($createdAt);
                $deadline = Carbon::instance($deadline);
                
                try {
                    Assignment::create([
                        'user_id' => $course->user_id, // Use the course instructor
                        'course_id' => $course->id,
                        'title' => $template['title'] . ' - ' . $course->code,
                        'slug' => \Str::slug($template['title'] . ' ' . $course->code),
                        'description' => $template['description'],
                        'code_sample' => $template['code_sample'],
                        'deadline' => $deadline,
                        'status' => fake()->randomElement(['active', 'draft', 'active', 'active']), // More likely to be active
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ]);
                    
                    $this->command->info("  ✓ Created assignment: {$template['title']} for {$course->code}");
                    
                } catch (\Exception $e) {
                    $this->command->error("  ✗ Failed to create assignment for {$course->code}: " . $e->getMessage());
                }
            }
        }

        $totalAssignments = Assignment::count();
        $this->command->info("Assignments seeded successfully! Total assignments: {$totalAssignments}");
    }
}