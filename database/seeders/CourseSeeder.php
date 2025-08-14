<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all instructors to assign courses to (changed from lecturers)
        $instructors = User::where('role', 'instructor')->get();

        if ($instructors->isEmpty()) {
            $this->command->warn('No instructors found. Please seed users first.');
            return;
        }

        $courses = [
            // Computer Science Courses
            [
                'title' => 'Introduction to Computer Science',
                'code' => 'CSC101',
                'description' => 'An introductory course covering fundamental concepts of computer science including programming basics, data structures, and algorithms.',
                'level' => '100',
                'semester' => 'first',
                'credit_units' => 3,
                'status' => 'active',
            ],
            [
                'title' => 'Programming Fundamentals',
                'code' => 'CSC102',
                'description' => 'Learn the basics of programming using modern programming languages. Covers variables, control structures, functions, and basic data structures.',
                'level' => '100',
                'semester' => 'second',
                'credit_units' => 4,
                'status' => 'active',
            ],
            [
                'title' => 'Data Structures and Algorithms',
                'code' => 'CSC201',
                'description' => 'Advanced study of data structures including arrays, linked lists, stacks, queues, trees, and graphs. Algorithm analysis and design.',
                'level' => '200',
                'semester' => 'first',
                'credit_units' => 3,
                'status' => 'active',
            ],
            [
                'title' => 'Object-Oriented Programming',
                'code' => 'CSC202',
                'description' => 'Comprehensive coverage of object-oriented programming concepts including classes, objects, inheritance, polymorphism, and encapsulation.',
                'level' => '200',
                'semester' => 'second',
                'credit_units' => 3,
                'status' => 'active',
            ],
            [
                'title' => 'Database Management Systems',
                'code' => 'CSC301',
                'description' => 'Introduction to database concepts, relational model, SQL, database design, normalization, and database administration.',
                'level' => '300',
                'semester' => 'first',
                'credit_units' => 3,
                'status' => 'active',
            ],
            [
                'title' => 'Web Development',
                'code' => 'CSC302',
                'description' => 'Modern web development techniques including HTML5, CSS3, JavaScript, responsive design, and web frameworks.',
                'level' => '300',
                'semester' => 'second',
                'credit_units' => 4,
                'status' => 'active',
            ],
            [
                'title' => 'Software Engineering',
                'code' => 'CSC401',
                'description' => 'Software development lifecycle, project management, testing, documentation, and software quality assurance.',
                'level' => '400',
                'semester' => 'first',
                'credit_units' => 3,
                'status' => 'active',
            ],
            [
                'title' => 'Artificial Intelligence',
                'code' => 'CSC402',
                'description' => 'Introduction to AI concepts including machine learning, neural networks, expert systems, and natural language processing.',
                'level' => '400',
                'semester' => 'second',
                'credit_units' => 3,
                'status' => 'draft',
            ],

            // Mathematics Courses
            [
                'title' => 'Calculus I',
                'code' => 'MTH101',
                'description' => 'Differential calculus including limits, derivatives, and applications of derivatives.',
                'level' => '100',
                'semester' => 'first',
                'credit_units' => 3,
                'status' => 'active',
            ],
            [
                'title' => 'Linear Algebra',
                'code' => 'MTH201',
                'description' => 'Vector spaces, matrices, determinants, eigenvalues, and linear transformations.',
                'level' => '200',
                'semester' => 'first',
                'credit_units' => 3,
                'status' => 'active',
            ],
            [
                'title' => 'Statistics and Probability',
                'code' => 'MTH301',
                'description' => 'Descriptive statistics, probability theory, distributions, hypothesis testing, and regression analysis.',
                'level' => '300',
                'semester' => 'second',
                'credit_units' => 3,
                'status' => 'active',
            ],

            // Physics Courses
            [
                'title' => 'General Physics I',
                'code' => 'PHY101',
                'description' => 'Mechanics, waves, and thermodynamics. Laboratory work included.',
                'level' => '100',
                'semester' => 'first',
                'credit_units' => 4,
                'status' => 'active',
            ],
            [
                'title' => 'Electricity and Magnetism',
                'code' => 'PHY201',
                'description' => 'Electric fields, magnetic fields, electromagnetic induction, and AC/DC circuits.',
                'level' => '200',
                'semester' => 'second',
                'credit_units' => 4,
                'status' => 'active',
            ],

            // English Courses
            [
                'title' => 'English Composition',
                'code' => 'ENG101',
                'description' => 'Academic writing, essay structure, research methods, and communication skills.',
                'level' => '100',
                'semester' => 'first',
                'credit_units' => 2,
                'status' => 'active',
            ],
            [
                'title' => 'Technical Writing',
                'code' => 'ENG201',
                'description' => 'Professional and technical communication, report writing, and presentation skills.',
                'level' => '200',
                'semester' => 'second',
                'credit_units' => 2,
                'status' => 'active',
            ],

            // Business Courses
            [
                'title' => 'Introduction to Business',
                'code' => 'BUS101',
                'description' => 'Fundamentals of business operations, management principles, and entrepreneurship.',
                'level' => '100',
                'semester' => 'first',
                'credit_units' => 3,
                'status' => 'active',
            ],
            [
                'title' => 'Project Management',
                'code' => 'BUS301',
                'description' => 'Project planning, execution, monitoring, and control. Risk management and team leadership.',
                'level' => '300',
                'semester' => 'first',
                'credit_units' => 3,
                'status' => 'inactive',
            ],

            // Elective/Special Courses
            [
                'title' => 'Mobile App Development',
                'code' => 'CSC350',
                'description' => 'iOS and Android app development using modern frameworks and tools.',
                'level' => '300',
                'semester' => 'second',
                'credit_units' => 4,
                'status' => 'draft',
            ],
            [
                'title' => 'Cybersecurity Fundamentals',
                'code' => 'CSC450',
                'description' => 'Network security, cryptography, ethical hacking, and security policies.',
                'level' => '400',
                'semester' => 'first',
                'credit_units' => 3,
                'status' => 'active',
            ],
            [
                'title' => 'Data Science and Analytics',
                'code' => 'CSC451',
                'description' => 'Big data processing, data mining, machine learning, and statistical analysis.',
                'level' => '400',
                'semester' => 'second',
                'credit_units' => 4,
                'status' => 'draft',
            ],
        ];

        foreach ($courses as $courseData) {
            // Randomly assign to an instructor (changed from lecturer)
            $instructor = $instructors->random();
            
            // Generate slug from title
            $slug = Str::slug($courseData['title']);
            
            // Ensure unique slug
            $originalSlug = $slug;
            $counter = 1;
            while (Course::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            Course::create([
                'user_id' => $instructor->id,
                'title' => $courseData['title'],
                'slug' => $slug,
                'code' => $courseData['code'],
                'description' => $courseData['description'],
                'level' => $courseData['level'],
                'semester' => $courseData['semester'],
                'credit_units' => $courseData['credit_units'],
                'status' => $courseData['status'],
                'image' => null, // You can add image paths here if needed
            ]);
        }

        $this->command->info('Courses seeded successfully!');
        $this->command->info('Created ' . count($courses) . ' courses assigned to ' . $instructors->count() . ' instructors.');
    }
}