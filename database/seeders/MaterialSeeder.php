<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;
use App\Models\Course;
use App\Models\User;
use Carbon\Carbon;

class MaterialSeeder extends Seeder
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

        $this->command->info("Creating materials for {$courses->count()} courses...");

        // Material templates for different types of content
        $materialTemplates = [
            // Lecture Notes
            [
                'title' => 'Introduction to Programming - Lecture Notes',
                'description' => 'Comprehensive lecture notes covering basic programming concepts, variables, data types, and control structures.',
                'file_type' => 'pdf',
                'file_size' => rand(500, 2000), // KB
                'visibility' => 'enrolled',
            ],
            [
                'title' => 'Data Structures Overview - Study Guide',
                'description' => 'Detailed study guide covering arrays, linked lists, stacks, queues, and their implementations.',
                'file_type' => 'pdf',
                'file_size' => rand(800, 1500),
                'visibility' => 'enrolled',
            ],
            [
                'title' => 'Algorithm Analysis - Presentation',
                'description' => 'PowerPoint presentation on Big O notation, time complexity, and space complexity analysis.',
                'file_type' => 'pptx',
                'file_size' => rand(2000, 5000),
                'visibility' => 'public',
            ],

            // Video Lectures
            [
                'title' => 'Object-Oriented Programming Fundamentals',
                'description' => 'Video lecture explaining classes, objects, inheritance, and polymorphism with practical examples.',
                'file_type' => 'mp4',
                'file_size' => rand(50000, 100000), // Larger for videos
                'visibility' => 'enrolled',
            ],
            [
                'title' => 'Database Design Tutorial',
                'description' => 'Step-by-step video tutorial on designing relational databases and normalization.',
                'file_type' => 'mp4',
                'file_size' => rand(75000, 120000),
                'visibility' => 'enrolled',
            ],

            // Code Examples
            [
                'title' => 'Sample Code - Sorting Algorithms',
                'description' => 'Implementation examples of bubble sort, merge sort, and quick sort in multiple programming languages.',
                'file_type' => 'zip',
                'file_size' => rand(100, 500),
                'visibility' => 'public',
            ],
            [
                'title' => 'Web Development Project Template',
                'description' => 'Starter template for web development projects including HTML, CSS, and JavaScript files.',
                'file_type' => 'zip',
                'file_size' => rand(1000, 3000),
                'visibility' => 'enrolled',
            ],

            // Reference Materials
            [
                'title' => 'Programming Language Reference Guide',
                'description' => 'Quick reference guide for syntax, built-in functions, and common patterns.',
                'file_type' => 'pdf',
                'file_size' => rand(300, 800),
                'visibility' => 'public',
            ],
            [
                'title' => 'Software Engineering Best Practices',
                'description' => 'Document outlining coding standards, version control practices, and project management guidelines.',
                'file_type' => 'docx',
                'file_size' => rand(400, 1200),
                'visibility' => 'enrolled',
            ],

            // Mathematics Materials
            [
                'title' => 'Calculus Fundamentals - Chapter 1',
                'description' => 'Introduction to limits, derivatives, and their applications in real-world problems.',
                'file_type' => 'pdf',
                'file_size' => rand(1000, 2500),
                'visibility' => 'enrolled',
            ],
            [
                'title' => 'Linear Algebra Worksheets',
                'description' => 'Practice problems and solutions for matrix operations, vector spaces, and eigenvalues.',
                'file_type' => 'pdf',
                'file_size' => rand(600, 1500),
                'visibility' => 'public',
            ],
            [
                'title' => 'Statistics and Probability Examples',
                'description' => 'Real-world examples and case studies demonstrating statistical concepts and probability distributions.',
                'file_type' => 'pptx',
                'file_size' => rand(3000, 6000),
                'visibility' => 'enrolled',
            ],

            // Additional Resources
            [
                'title' => 'Course Syllabus and Schedule',
                'description' => 'Complete course outline, learning objectives, assessment criteria, and weekly schedule.',
                'file_type' => 'pdf',
                'file_size' => rand(200, 500),
                'visibility' => 'public',
            ],
            [
                'title' => 'Recommended Reading List',
                'description' => 'Curated list of textbooks, articles, and online resources for further study.',
                'file_type' => 'docx',
                'file_size' => rand(150, 400),
                'visibility' => 'public',
            ],
            [
                'title' => 'Lab Exercise Instructions',
                'description' => 'Detailed instructions for hands-on laboratory exercises and practical assignments.',
                'file_type' => 'pdf',
                'file_size' => rand(800, 2000),
                'visibility' => 'enrolled',
            ],
        ];

        foreach ($courses as $course) {
            // Create 3-6 materials per course
            $materialCount = rand(3, 6);
            
            $this->command->info("Creating {$materialCount} materials for course: {$course->title}");
            
            for ($i = 0; $i < $materialCount; $i++) {
                $template = fake()->randomElement($materialTemplates);
                
                // Customize title based on course
                $customTitle = $this->customizeTitleForCourse($template['title'], $course);
                
                // Create upload date between course creation and now
                $courseCreated = Carbon::parse($course->created_at);
                $uploadedAt = fake()->dateTimeBetween($courseCreated, 'now');
                
                // Convert to Carbon instance
                $uploadedAt = Carbon::instance($uploadedAt);
                
                // Generate fake file path
                $filePath = $this->generateFilePath($course, $customTitle, $template['file_type']);
                
                try {
                    Material::create([
                        'user_id' => $course->user_id, // Use the course instructor
                        'course_id' => $course->id,
                        'title' => $customTitle,
                        'description' => $template['description'],
                        'file_path' => $filePath,
                        'file_type' => $template['file_type'],
                        'file_size' => $template['file_size'],
                        'visibility' => $template['visibility'],
                        'uploaded_at' => $uploadedAt,
                        'created_at' => $uploadedAt,
                        'updated_at' => $uploadedAt,
                    ]);
                    
                    $this->command->info("  ✓ Created material: {$customTitle}");
                    
                } catch (\Exception $e) {
                    $this->command->error("  ✗ Failed to create material for {$course->code}: " . $e->getMessage());
                }
            }
        }

        $totalMaterials = Material::count();
        $this->command->info("Materials seeded successfully! Total materials: {$totalMaterials}");
        
        // Show summary by instructor
        foreach ($instructors as $instructor) {
            $instructorMaterials = Material::where('user_id', $instructor->id)->count();
            $this->command->info("  - {$instructor->name}: {$instructorMaterials} materials");
        }
    }

    /**
     * Customize material title based on course content
     */
    private function customizeTitleForCourse(string $title, $course): string
    {
        $courseCode = $course->code;
        $courseTitle = $course->title;
        
        // Map course types to relevant materials
        $courseMappings = [
            'CSC' => [
                'Programming' => ['Introduction to Programming', 'Object-Oriented Programming', 'Sample Code', 'Web Development'],
                'Data Structures' => ['Data Structures Overview', 'Algorithm Analysis', 'Sample Code - Sorting'],
                'Database' => ['Database Design Tutorial', 'SQL Reference Guide'],
                'Software Engineering' => ['Software Engineering Best Practices', 'Project Management'],
            ],
            'MTH' => [
                'Calculus' => ['Calculus Fundamentals', 'Mathematical Analysis'],
                'Linear Algebra' => ['Linear Algebra Worksheets', 'Matrix Operations Guide'],
                'Statistics' => ['Statistics and Probability Examples', 'Statistical Analysis'],
            ],
            'PHY' => [
                'Physics' => ['Physics Fundamentals', 'Laboratory Experiments'],
                'Mechanics' => ['Classical Mechanics Notes', 'Problem Solutions'],
            ],
            'ENG' => [
                'English' => ['Writing Guidelines', 'Grammar Reference'],
                'Technical Writing' => ['Technical Communication Guide', 'Report Writing'],
            ],
            'BUS' => [
                'Business' => ['Business Fundamentals', 'Case Studies'],
                'Management' => ['Management Principles', 'Leadership Guide'],
            ],
        ];

        // Extract course prefix (e.g., CSC, MTH)
        $prefix = substr($courseCode, 0, 3);
        
        // Try to match course content and customize title
        if (isset($courseMappings[$prefix])) {
            foreach ($courseMappings[$prefix] as $topic => $materials) {
                if (stripos($courseTitle, $topic) !== false) {
                    $relevantMaterial = fake()->randomElement($materials);
                    return "{$relevantMaterial} - {$courseCode}";
                }
            }
        }
        
        // Fallback: use original title with course code
        return "{$title} - {$courseCode}";
    }

    /**
     * Generate a fake file path for the material
     */
    private function generateFilePath($course, string $title, string $fileType): string
    {
        $courseCode = strtolower($course->code);
        $fileName = \Str::slug($title) . '.' . $fileType;
        
        return "materials/{$courseCode}/{$fileName}";
    }
}