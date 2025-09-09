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
        // Get all instructors to assign courses to
        $instructors = User::where('role', 'instructor')->get();

        if ($instructors->isEmpty()) {
            $this->command->warn('No instructors found. Please seed users first.');
            return;
        }

        $courses = [
            // 100 Level CMP Courses
            [
                'title' => 'Introduction to Computer Programming',
                'code' => 'CMP101',
                'description' => 'An introductory course covering fundamental concepts of computer programming including problem solving, algorithm design, and basic programming constructs.',
                'level' => '100',
                'semester' => 'first',
                'credit_units' => 3,
                'status' => 'active',
            ],
            [
                'title' => 'Computer Appreciation and Applications',
                'code' => 'CMP102',
                'description' => 'Introduction to computer systems, hardware, software, and basic applications including word processing, spreadsheets, and presentations.',
                'level' => '100',
                'semester' => 'second',
                'credit_units' => 2,
                'status' => 'active',
            ],
            [
                'title' => 'Programming Fundamentals I',
                'code' => 'CMP103',
                'description' => 'Learn the basics of programming using C/C++. Covers variables, data types, control structures, and functions.',
                'level' => '100',
                'semester' => 'first',
                'credit_units' => 4,
                'status' => 'active',
            ],
            [
                'title' => 'Programming Fundamentals II',
                'code' => 'CMP104',
                'description' => 'Advanced programming concepts including arrays, pointers, file handling, and modular programming.',
                'level' => '100',
                'semester' => 'second',
                'credit_units' => 4,
                'status' => 'active',
            ],
            [
                'title' => 'Digital Logic Design',
                'code' => 'CMP105',
                'description' => 'Boolean algebra, logic gates, combinational and sequential circuits, and basic computer architecture.',
                'level' => '100',
                'semester' => 'second',
                'credit_units' => 3,
                'status' => 'active',
            ],

            // 200 Level CMP Courses
            [
                'title' => 'Data Structures and Algorithms',
                'code' => 'CMP201',
                'description' => 'Study of fundamental data structures including arrays, linked lists, stacks, queues, trees, and graphs. Algorithm analysis and complexity.',
                'level' => '200',
                'semester' => 'first',
                'credit_units' => 4,
                'status' => 'active',
            ],
            [
                'title' => 'Object-Oriented Programming',
                'code' => 'CMP202',
                'description' => 'Comprehensive coverage of OOP concepts using Java/C++ including classes, objects, inheritance, polymorphism, and encapsulation.',
                'level' => '200',
                'semester' => 'second',
                'credit_units' => 4,
                'status' => 'active',
            ],
            [
                'title' => 'Computer Architecture and Organization',
                'code' => 'CMP203',
                'description' => 'Computer system organization, processor design, memory hierarchy, input/output systems, and assembly language programming.',
                'level' => '200',
                'semester' => 'first',
                'credit_units' => 3,
                'status' => 'active',
            ],
            [
                'title' => 'Discrete Mathematics for Computer Science',
                'code' => 'CMP204',
                'description' => 'Mathematical foundations including set theory, logic, relations, functions, graph theory, and combinatorics.',
                'level' => '200',
                'semester' => 'second',
                'credit_units' => 3,
                'status' => 'active',
            ],
            [
                'title' => 'Database Management Systems I',
                'code' => 'CMP205',
                'description' => 'Introduction to database concepts, relational model, SQL, database design, and normalization.',
                'level' => '200',
                'semester' => 'first',
                'credit_units' => 3,
                'status' => 'active',
            ],
            [
                'title' => 'Web Programming I',
                'code' => 'CMP206',
                'description' => 'Client-side web development using HTML5, CSS3, JavaScript, and responsive design principles.',
                'level' => '200',
                'semester' => 'second',
                'credit_units' => 3,
                'status' => 'active',
            ],
            [
                'title' => 'Systems Analysis and Design',
                'code' => 'CMP207',
                'description' => 'System development lifecycle, requirements analysis, system modeling, and design methodologies.',
                'level' => '200',
                'semester' => 'second',
                'credit_units' => 3,
                'status' => 'active',
            ],

            // 300 Level CMP Courses
            [
                'title' => 'Advanced Database Systems',
                'code' => 'CMP301',
                'description' => 'Advanced database topics including query optimization, transaction management, distributed databases, and NoSQL systems.',
                'level' => '300',
                'semester' => 'first',
                'credit_units' => 3,
                'status' => 'active',
            ],
            [
                'title' => 'Operating Systems',
                'code' => 'CMP302',
                'description' => 'Operating system concepts including process management, memory management, file systems, and system security.',
                'level' => '300',
                'semester' => 'second',
                'credit_units' => 4,
                'status' => 'active',
            ],
            [
                'title' => 'Software Engineering I',
                'code' => 'CMP303',
                'description' => 'Software development lifecycle, project management, requirements engineering, and software design patterns.',
                'level' => '300',
                'semester' => 'first',
                'credit_units' => 3,
                'status' => 'active',
            ],
            [
                'title' => 'Computer Networks',
                'code' => 'CMP304',
                'description' => 'Network protocols, TCP/IP, network architecture, routing, switching, and network security basics.',
                'level' => '300',
                'semester' => 'second',
                'credit_units' => 3,
                'status' => 'active',
            ],
            [
                'title' => 'Artificial Intelligence I',
                'code' => 'CMP305',
                'description' => 'Introduction to AI concepts including search algorithms, knowledge representation, and machine learning basics.',
                'level' => '300',
                'semester' => 'first',
                'credit_units' => 3,
                'status' => 'active',
            ],
            [
                'title' => 'Web Programming II',
                'code' => 'CMP306',
                'description' => 'Server-side web development using PHP/Python/Node.js, frameworks, APIs, and full-stack development.',
                'level' => '300',
                'semester' => 'second',
                'credit_units' => 4,
                'status' => 'active',
            ],
            [
                'title' => 'Mobile Application Development',
                'code' => 'CMP307',
                'description' => 'iOS and Android app development using native and cross-platform frameworks like React Native or Flutter.',
                'level' => '300',
                'semester' => 'first',
                'credit_units' => 4,
                'status' => 'active',
            ],
            [
                'title' => 'Human-Computer Interaction',
                'code' => 'CMP308',
                'description' => 'User interface design, usability testing, user experience principles, and interaction design methodologies.',
                'level' => '300',
                'semester' => 'second',
                'credit_units' => 3,
                'status' => 'active',
            ],
            [
                'title' => 'Compiler Design',
                'code' => 'CMP309',
                'description' => 'Lexical analysis, parsing, semantic analysis, code generation, and optimization techniques.',
                'level' => '300',
                'semester' => 'first',
                'credit_units' => 3,
                'status' => 'draft',
            ],

            // 400 Level CMP Courses
            [
                'title' => 'Software Engineering II',
                'code' => 'CMP401',
                'description' => 'Advanced software engineering topics including testing, maintenance, quality assurance, and software metrics.',
                'level' => '400',
                'semester' => 'first',
                'credit_units' => 3,
                'status' => 'active',
            ],
            [
                'title' => 'Artificial Intelligence II',
                'code' => 'CMP402',
                'description' => 'Advanced AI topics including neural networks, deep learning, natural language processing, and computer vision.',
                'level' => '400',
                'semester' => 'second',
                'credit_units' => 4,
                'status' => 'active',
            ],
            [
                'title' => 'Cybersecurity and Information Assurance',
                'code' => 'CMP403',
                'description' => 'Network security, cryptography, ethical hacking, security policies, and risk management.',
                'level' => '400',
                'semester' => 'first',
                'credit_units' => 3,
                'status' => 'active',
            ],
            [
                'title' => 'Data Science and Big Data Analytics',
                'code' => 'CMP404',
                'description' => 'Big data processing, data mining, machine learning, statistical analysis, and data visualization.',
                'level' => '400',
                'semester' => 'second',
                'credit_units' => 4,
                'status' => 'active',
            ],
            [
                'title' => 'Cloud Computing',
                'code' => 'CMP405',
                'description' => 'Cloud architecture, virtualization, containerization, microservices, and cloud service platforms.',
                'level' => '400',
                'semester' => 'first',
                'credit_units' => 3,
                'status' => 'active',
            ],
            [
                'title' => 'Computer Graphics and Multimedia',
                'code' => 'CMP406',
                'description' => '2D/3D graphics, animation, image processing, multimedia systems, and game development basics.',
                'level' => '400',
                'semester' => 'second',
                'credit_units' => 3,
                'status' => 'active',
            ],
            [
                'title' => 'Distributed Systems',
                'code' => 'CMP407',
                'description' => 'Distributed computing concepts, fault tolerance, consistency, consensus algorithms, and blockchain technology.',
                'level' => '400',
                'semester' => 'first',
                'credit_units' => 3,
                'status' => 'draft',
            ],
            [
                'title' => 'Advanced Computer Networks',
                'code' => 'CMP408',
                'description' => 'Network performance analysis, wireless networks, network management, and emerging network technologies.',
                'level' => '400',
                'semester' => 'second',
                'credit_units' => 3,
                'status' => 'active',
            ],
            [
                'title' => 'Project Management in Computing',
                'code' => 'CMP409',
                'description' => 'IT project management, agile methodologies, risk management, and team leadership in software projects.',
                'level' => '400',
                'semester' => 'first',
                'credit_units' => 3,
                'status' => 'active',
            ],
            [
                'title' => 'Final Year Project I',
                'code' => 'CMP410',
                'description' => 'Independent research project, literature review, project proposal, and initial implementation.',
                'level' => '400',
                'semester' => 'first',
                'credit_units' => 4,
                'status' => 'active',
            ],
            [
                'title' => 'Final Year Project II',
                'code' => 'CMP411',
                'description' => 'Continuation of final year project, implementation, testing, documentation, and presentation.',
                'level' => '400',
                'semester' => 'second',
                'credit_units' => 4,
                'status' => 'active',
            ],

            // Statistics (STA) Courses
            [
                'title' => 'Introduction to Statistics',
                'code' => 'STA101',
                'description' => 'Basic statistical concepts, descriptive statistics, data collection methods, and introduction to probability.',
                'level' => '100',
                'semester' => 'first',
                'credit_units' => 3,
                'status' => 'active',
            ],
            [
                'title' => 'Probability Theory I',
                'code' => 'STA102',
                'description' => 'Fundamental probability concepts, random variables, probability distributions, and expectation.',
                'level' => '100',
                'semester' => 'second',
                'credit_units' => 3,
                'status' => 'active',
            ],
            [
                'title' => 'Statistical Inference I',
                'code' => 'STA201',
                'description' => 'Estimation theory, confidence intervals, hypothesis testing, and goodness of fit tests.',
                'level' => '200',
                'semester' => 'first',
                'credit_units' => 3,
                'status' => 'active',
            ],
            [
                'title' => 'Regression Analysis',
                'code' => 'STA202',
                'description' => 'Simple and multiple linear regression, model diagnostics, and correlation analysis.',
                'level' => '200',
                'semester' => 'second',
                'credit_units' => 3,
                'status' => 'active',
            ],
            [
                'title' => 'Design of Experiments',
                'code' => 'STA301',
                'description' => 'Experimental design principles, ANOVA, factorial designs, and randomized block designs.',
                'level' => '300',
                'semester' => 'first',
                'credit_units' => 3,
                'status' => 'active',
            ],
            [
                'title' => 'Time Series Analysis',
                'code' => 'STA302',
                'description' => 'Time series components, forecasting methods, ARIMA models, and seasonal analysis.',
                'level' => '300',
                'semester' => 'second',
                'credit_units' => 3,
                'status' => 'active',
            ],
            [
                'title' => 'Multivariate Statistics',
                'code' => 'STA401',
                'description' => 'Principal component analysis, factor analysis, cluster analysis, and discriminant analysis.',
                'level' => '400',
                'semester' => 'first',
                'credit_units' => 3,
                'status' => 'active',
            ],
            [
                'title' => 'Statistical Computing',
                'code' => 'STA402',
                'description' => 'Statistical software packages (R, SPSS, SAS), data manipulation, and computational statistics.',
                'level' => '400',
                'semester' => 'second',
                'credit_units' => 3,
                'status' => 'active',
            ],
        ];

        foreach ($courses as $courseData) {
            // Randomly assign to an instructor
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
        $this->command->info('Created ' . count($courses) . ' courses (CMP & STA) assigned to ' . $instructors->count() . ' instructors.');
        
        // Display statistics
        $cmpCourses = collect($courses)->where('code', 'like', 'CMP%')->count();
        $staCourses = collect($courses)->where('code', 'like', 'STA%')->count();
        $level100 = collect($courses)->where('level', '100')->count();
        $level200 = collect($courses)->where('level', '200')->count();
        $level300 = collect($courses)->where('level', '300')->count();
        $level400 = collect($courses)->where('level', '400')->count();
        
        $this->command->info("Course Statistics:");
        $this->command->info("- CMP Courses: {$cmpCourses}");
        $this->command->info("- STA Courses: {$staCourses}");
        $this->command->info("- 100 Level: {$level100}");
        $this->command->info("- 200 Level: {$level200}");
        $this->command->info("- 300 Level: {$level300}");
        $this->command->info("- 400 Level: {$level400}");
    }
}