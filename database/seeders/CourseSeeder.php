<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        
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
                'image_category' => 'programming',
                'keywords' => ['programming', 'coding', 'computer', 'development']
            ],
            [
                'title' => 'Computer Appreciation and Applications',
                'code' => 'CMP102',
                'description' => 'Introduction to computer systems, hardware, software, and basic applications including word processing, spreadsheets, and presentations.',
                'level' => '100',
                'semester' => 'second',
                'credit_units' => 2,
                'status' => 'active',
                'image_category' => 'computer',
                'keywords' => ['computer', 'technology', 'hardware', 'software']
            ],
            [
                'title' => 'Programming Fundamentals I',
                'code' => 'CMP103',
                'description' => 'Learn the basics of programming using C/C++. Covers variables, data types, control structures, and functions.',
                'level' => '100',
                'semester' => 'first',
                'credit_units' => 4,
                'status' => 'active',
                'image_category' => 'coding',
                'keywords' => ['coding', 'programming', 'c++', 'development']
            ],
            [
                'title' => 'Programming Fundamentals II',
                'code' => 'CMP104',
                'description' => 'Advanced programming concepts including arrays, pointers, file handling, and modular programming.',
                'level' => '100',
                'semester' => 'second',
                'credit_units' => 4,
                'status' => 'active',
                'image_category' => 'programming',
                'keywords' => ['programming', 'coding', 'algorithms', 'development']
            ],
            [
                'title' => 'Digital Logic Design',
                'code' => 'CMP105',
                'description' => 'Boolean algebra, logic gates, combinational and sequential circuits, and basic computer architecture.',
                'level' => '100',
                'semester' => 'second',
                'credit_units' => 3,
                'status' => 'active',
                'image_category' => 'electronics',
                'keywords' => ['electronics', 'circuits', 'digital', 'logic']
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
                'image_category' => 'algorithms',
                'keywords' => ['algorithms', 'data-structures', 'programming', 'computer-science']
            ],
            [
                'title' => 'Object-Oriented Programming',
                'code' => 'CMP202',
                'description' => 'Comprehensive coverage of OOP concepts using Java/C++ including classes, objects, inheritance, polymorphism, and encapsulation.',
                'level' => '200',
                'semester' => 'second',
                'credit_units' => 4,
                'status' => 'active',
                'image_category' => 'programming',
                'keywords' => ['java', 'oop', 'programming', 'software']
            ],
            [
                'title' => 'Computer Architecture and Organization',
                'code' => 'CMP203',
                'description' => 'Computer system organization, processor design, memory hierarchy, input/output systems, and assembly language programming.',
                'level' => '200',
                'semester' => 'first',
                'credit_units' => 3,
                'status' => 'active',
                'image_category' => 'hardware',
                'keywords' => ['hardware', 'computer', 'processor', 'architecture']
            ],
            [
                'title' => 'Discrete Mathematics for Computer Science',
                'code' => 'CMP204',
                'description' => 'Mathematical foundations including set theory, logic, relations, functions, graph theory, and combinatorics.',
                'level' => '200',
                'semester' => 'second',
                'credit_units' => 3,
                'status' => 'active',
                'image_category' => 'mathematics',
                'keywords' => ['mathematics', 'logic', 'graphs', 'theory']
            ],
            [
                'title' => 'Database Management Systems I',
                'code' => 'CMP205',
                'description' => 'Introduction to database concepts, relational model, SQL, database design, and normalization.',
                'level' => '200',
                'semester' => 'first',
                'credit_units' => 3,
                'status' => 'active',
                'image_category' => 'database',
                'keywords' => ['database', 'sql', 'data', 'storage']
            ],
            [
                'title' => 'Web Programming I',
                'code' => 'CMP206',
                'description' => 'Client-side web development using HTML5, CSS3, JavaScript, and responsive design principles.',
                'level' => '200',
                'semester' => 'second',
                'credit_units' => 3,
                'status' => 'active',
                'image_category' => 'web-development',
                'keywords' => ['web', 'html', 'css', 'javascript']
            ],
            [
                'title' => 'Systems Analysis and Design',
                'code' => 'CMP207',
                'description' => 'System development lifecycle, requirements analysis, system modeling, and design methodologies.',
                'level' => '200',
                'semester' => 'second',
                'credit_units' => 3,
                'status' => 'active',
                'image_category' => 'design',
                'keywords' => ['design', 'systems', 'analysis', 'modeling']
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
                'image_category' => 'database',
                'keywords' => ['database', 'nosql', 'distributed', 'optimization']
            ],
            [
                'title' => 'Operating Systems',
                'code' => 'CMP302',
                'description' => 'Operating system concepts including process management, memory management, file systems, and system security.',
                'level' => '300',
                'semester' => 'second',
                'credit_units' => 4,
                'status' => 'active',
                'image_category' => 'operating-systems',
                'keywords' => ['operating-system', 'linux', 'windows', 'system']
            ],
            [
                'title' => 'Software Engineering I',
                'code' => 'CMP303',
                'description' => 'Software development lifecycle, project management, requirements engineering, and software design patterns.',
                'level' => '300',
                'semester' => 'first',
                'credit_units' => 3,
                'status' => 'active',
                'image_category' => 'software-engineering',
                'keywords' => ['software', 'engineering', 'development', 'project']
            ],
            [
                'title' => 'Computer Networks',
                'code' => 'CMP304',
                'description' => 'Network protocols, TCP/IP, network architecture, routing, switching, and network security basics.',
                'level' => '300',
                'semester' => 'second',
                'credit_units' => 3,
                'status' => 'active',
                'image_category' => 'networking',
                'keywords' => ['network', 'internet', 'tcp-ip', 'routing']
            ],
            [
                'title' => 'Artificial Intelligence I',
                'code' => 'CMP305',
                'description' => 'Introduction to AI concepts including search algorithms, knowledge representation, and machine learning basics.',
                'level' => '300',
                'semester' => 'first',
                'credit_units' => 3,
                'status' => 'active',
                'image_category' => 'artificial-intelligence',
                'keywords' => ['ai', 'machine-learning', 'artificial-intelligence', 'algorithms']
            ],
            [
                'title' => 'Web Programming II',
                'code' => 'CMP306',
                'description' => 'Server-side web development using PHP/Python/Node.js, frameworks, APIs, and full-stack development.',
                'level' => '300',
                'semester' => 'second',
                'credit_units' => 4,
                'status' => 'active',
                'image_category' => 'web-development',
                'keywords' => ['web', 'php', 'python', 'nodejs']
            ],
            [
                'title' => 'Mobile Application Development',
                'code' => 'CMP307',
                'description' => 'iOS and Android app development using native and cross-platform frameworks like React Native or Flutter.',
                'level' => '300',
                'semester' => 'first',
                'credit_units' => 4,
                'status' => 'active',
                'image_category' => 'mobile',
                'keywords' => ['mobile', 'app', 'android', 'ios']
            ],
            [
                'title' => 'Human-Computer Interaction',
                'code' => 'CMP308',
                'description' => 'User interface design, usability testing, user experience principles, and interaction design methodologies.',
                'level' => '300',
                'semester' => 'second',
                'credit_units' => 3,
                'status' => 'active',
                'image_category' => 'ui-ux',
                'keywords' => ['ui', 'ux', 'design', 'interface']
            ],
            [
                'title' => 'Compiler Design',
                'code' => 'CMP309',
                'description' => 'Lexical analysis, parsing, semantic analysis, code generation, and optimization techniques.',
                'level' => '300',
                'semester' => 'first',
                'credit_units' => 3,
                'status' => 'draft',
                'image_category' => 'programming',
                'keywords' => ['compiler', 'programming', 'parsing', 'code']
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
                'image_category' => 'software-engineering',
                'keywords' => ['software', 'testing', 'quality', 'engineering']
            ],
            [
                'title' => 'Artificial Intelligence II',
                'code' => 'CMP402',
                'description' => 'Advanced AI topics including neural networks, deep learning, natural language processing, and computer vision.',
                'level' => '400',
                'semester' => 'second',
                'credit_units' => 4,
                'status' => 'active',
                'image_category' => 'machine-learning',
                'keywords' => ['ai', 'neural-networks', 'deep-learning', 'computer-vision']
            ],
            [
                'title' => 'Cybersecurity and Information Assurance',
                'code' => 'CMP403',
                'description' => 'Network security, cryptography, ethical hacking, security policies, and risk management.',
                'level' => '400',
                'semester' => 'first',
                'credit_units' => 3,
                'status' => 'active',
                'image_category' => 'cybersecurity',
                'keywords' => ['security', 'cybersecurity', 'hacking', 'encryption']
            ],
            [
                'title' => 'Data Science and Big Data Analytics',
                'code' => 'CMP404',
                'description' => 'Big data processing, data mining, machine learning, statistical analysis, and data visualization.',
                'level' => '400',
                'semester' => 'second',
                'credit_units' => 4,
                'status' => 'active',
                'image_category' => 'data-science',
                'keywords' => ['data-science', 'big-data', 'analytics', 'visualization']
            ],
            [
                'title' => 'Cloud Computing',
                'code' => 'CMP405',
                'description' => 'Cloud architecture, virtualization, containerization, microservices, and cloud service platforms.',
                'level' => '400',
                'semester' => 'first',
                'credit_units' => 3,
                'status' => 'active',
                'image_category' => 'cloud',
                'keywords' => ['cloud', 'aws', 'azure', 'virtualization']
            ],
            [
                'title' => 'Computer Graphics and Multimedia',
                'code' => 'CMP406',
                'description' => '2D/3D graphics, animation, image processing, multimedia systems, and game development basics.',
                'level' => '400',
                'semester' => 'second',
                'credit_units' => 3,
                'status' => 'active',
                'image_category' => 'graphics',
                'keywords' => ['graphics', '3d', 'animation', 'multimedia']
            ],
            [
                'title' => 'Distributed Systems',
                'code' => 'CMP407',
                'description' => 'Distributed computing concepts, fault tolerance, consistency, consensus algorithms, and blockchain technology.',
                'level' => '400',
                'semester' => 'first',
                'credit_units' => 3,
                'status' => 'draft',
                'image_category' => 'distributed-systems',
                'keywords' => ['distributed', 'blockchain', 'consensus', 'systems']
            ],
            [
                'title' => 'Advanced Computer Networks',
                'code' => 'CMP408',
                'description' => 'Network performance analysis, wireless networks, network management, and emerging network technologies.',
                'level' => '400',
                'semester' => 'second',
                'credit_units' => 3,
                'status' => 'active',
                'image_category' => 'networking',
                'keywords' => ['network', 'wireless', 'performance', 'technology']
            ],
            [
                'title' => 'Project Management in Computing',
                'code' => 'CMP409',
                'description' => 'IT project management, agile methodologies, risk management, and team leadership in software projects.',
                'level' => '400',
                'semester' => 'first',
                'credit_units' => 3,
                'status' => 'active',
                'image_category' => 'project-management',
                'keywords' => ['project', 'management', 'agile', 'leadership']
            ],
            [
                'title' => 'Final Year Project I',
                'code' => 'CMP410',
                'description' => 'Independent research project, literature review, project proposal, and initial implementation.',
                'level' => '400',
                'semester' => 'first',
                'credit_units' => 4,
                'status' => 'active',
                'image_category' => 'research',
                'keywords' => ['research', 'project', 'thesis', 'academic']
            ],
            [
                'title' => 'Final Year Project II',
                'code' => 'CMP411',
                'description' => 'Continuation of final year project, implementation, testing, documentation, and presentation.',
                'level' => '400',
                'semester' => 'second',
                'credit_units' => 4,
                'status' => 'active',
                'image_category' => 'research',
                'keywords' => ['research', 'project', 'presentation', 'documentation']
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
                'image_category' => 'statistics',
                'keywords' => ['statistics', 'data', 'probability', 'analysis']
            ],
            [
                'title' => 'Probability Theory I',
                'code' => 'STA102',
                'description' => 'Fundamental probability concepts, random variables, probability distributions, and expectation.',
                'level' => '100',
                'semester' => 'second',
                'credit_units' => 3,
                'status' => 'active',
                'image_category' => 'mathematics',
                'keywords' => ['probability', 'mathematics', 'statistics', 'theory']
            ],
            [
                'title' => 'Statistical Inference I',
                'code' => 'STA201',
                'description' => 'Estimation theory, confidence intervals, hypothesis testing, and goodness of fit tests.',
                'level' => '200',
                'semester' => 'first',
                'credit_units' => 3,
                'status' => 'active',
                'image_category' => 'statistics',
                'keywords' => ['statistics', 'inference', 'hypothesis', 'testing']
            ],
            [
                'title' => 'Regression Analysis',
                'code' => 'STA202',
                'description' => 'Simple and multiple linear regression, model diagnostics, and correlation analysis.',
                'level' => '200',
                'semester' => 'second',
                'credit_units' => 3,
                'status' => 'active',
                'image_category' => 'data-analysis',
                'keywords' => ['regression', 'analysis', 'statistics', 'modeling']
            ],
            [
                'title' => 'Design of Experiments',
                'code' => 'STA301',
                'description' => 'Experimental design principles, ANOVA, factorial designs, and randomized block designs.',
                'level' => '300',
                'semester' => 'first',
                'credit_units' => 3,
                'status' => 'active',
                'image_category' => 'research',
                'keywords' => ['experiment', 'design', 'research', 'anova']
            ],
            [
                'title' => 'Time Series Analysis',
                'code' => 'STA302',
                'description' => 'Time series components, forecasting methods, ARIMA models, and seasonal analysis.',
                'level' => '300',
                'semester' => 'second',
                'credit_units' => 3,
                'status' => 'active',
                'image_category' => 'data-analysis',
                'keywords' => ['time-series', 'forecasting', 'analysis', 'data']
            ],
            [
                'title' => 'Multivariate Statistics',
                'code' => 'STA401',
                'description' => 'Principal component analysis, factor analysis, cluster analysis, and discriminant analysis.',
                'level' => '400',
                'semester' => 'first',
                'credit_units' => 3,
                'status' => 'active',
                'image_category' => 'statistics',
                'keywords' => ['multivariate', 'statistics', 'analysis', 'clustering']
            ],
            [
                'title' => 'Statistical Computing',
                'code' => 'STA402',
                'description' => 'Statistical software packages (R, SPSS, SAS), data manipulation, and computational statistics.',
                'level' => '400',
                'semester' => 'second',
                'credit_units' => 3,
                'status' => 'active',
                'image_category' => 'data-science',
                'keywords' => ['r', 'statistics', 'computing', 'data']
            ],
        ];

        // Create courses directory if it doesn't exist
        if (!Storage::disk('public')->exists('courses')) {
            Storage::disk('public')->makeDirectory('courses');
        }

        $imagesDownloaded = 0;
        $imagesCached = 0;

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

            // Generate course image (with caching)
            $imageResult = $this->generateCourseImage($faker, $courseData);
            $imagePath = $imageResult['path'];
            
            if ($imageResult['cached']) {
                $imagesCached++;
            } elseif ($imagePath) {
                $imagesDownloaded++;
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
                'image' => $imagePath,
            ]);
        }

        $this->command->info('Courses seeded successfully!');
        $this->command->info('Created ' . count($courses) . ' courses (CMP & STA) assigned to ' . $instructors->count() . ' instructors.');
        $this->command->info("Images: {$imagesCached} cached, {$imagesDownloaded} downloaded");
        
        // Display statistics
        $cmpCourses = collect($courses)->filter(fn($course) => str_starts_with($course['code'], 'CMP'))->count();
        $staCourses = collect($courses)->filter(fn($course) => str_starts_with($course['code'], 'STA'))->count();
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

    /**
     * Check if image already exists in storage
     */
    private function checkExistingImage($courseCode): ?string
    {
        $courseSlug = Str::slug($courseCode);
        $possibleExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        
        foreach ($possibleExtensions as $ext) {
            $filename = "courses/{$courseSlug}.{$ext}";
            if (Storage::disk('public')->exists($filename)) {
                return $filename;
            }
            
            // Also check with pattern that includes timestamp (from previous runs)
            $files = Storage::disk('public')->files('courses');
            foreach ($files as $file) {
                if (str_contains($file, $courseSlug) && str_ends_with($file, ".{$ext}")) {
                    return $file;
                }
            }
        }
        
        return null;
    }

    /**
     * Generate course image using Faker and Unsplash with caching
     */
    private function generateCourseImage($faker, $courseData): array
    {
        // First, check if image already exists
        $existingImage = $this->checkExistingImage($courseData['code']);
        if ($existingImage) {
            $this->command->info("Using cached image for {$courseData['code']}: {$existingImage}");
            return [
                'path' => $existingImage,
                'cached' => true
            ];
        }

        try {
            // Method 1: Use Unsplash API (recommended)
            $imagePath = $this->downloadUnsplashImage($courseData);
            if ($imagePath) {
                $this->command->info("Downloaded Unsplash image for {$courseData['code']}: {$imagePath}");
                return [
                    'path' => $imagePath,
                    'cached' => false
                ];
            }

            // Method 2: Use Faker's image method with Lorem Picsum
            $imagePath = $this->generateFakerImage($faker, $courseData);
            if ($imagePath) {
                $this->command->info("Generated Faker image for {$courseData['code']}: {$imagePath}");
                return [
                    'path' => $imagePath,
                    'cached' => false
                ];
            }

            // Method 3: Use Picsum with category-based seed
            $imagePath = $this->generatePicsumImage($courseData);
            if ($imagePath) {
                $this->command->info("Generated Picsum image for {$courseData['code']}: {$imagePath}");
                return [
                    'path' => $imagePath,
                    'cached' => false
                ];
            }

        } catch (\Exception $e) {
            $this->command->warn("Failed to generate image for {$courseData['code']}: " . $e->getMessage());
        }

        return [
            'path' => null,
            'cached' => false
        ];
    }

    /**
     * Download image from Unsplash API
     */
    private function downloadUnsplashImage($courseData): ?string
    {
        try {
            // You'll need to get a free API key from https://unsplash.com/developers
            $unsplashAccessKey = env('UNSPLASH_ACCESS_KEY');
            
            if (!$unsplashAccessKey) {
                return null;
            }

            $keyword = $courseData['keywords'][0] ?? $courseData['image_category'];
            
            $response = Http::timeout(30)->get('https://api.unsplash.com/photos/random', [
                'client_id' => $unsplashAccessKey,
                'query' => $keyword,
                'w' => 800,
                'h' => 600,
                'fit' => 'crop'
            ]);

            if ($response->successful()) {
                $imageData = $response->json();
                $imageUrl = $imageData['urls']['regular'];
                
                // Download and save the image with consistent naming
                $imageContent = Http::timeout(30)->get($imageUrl)->body();
                $filename = 'courses/' . Str::slug($courseData['code']) . '.jpg';
                
                Storage::disk('public')->put($filename, $imageContent);
                
                return $filename;
            }
        } catch (\Exception $e) {
            // Fall back to other methods
        }

        return null;
    }

    /**
     * Generate image using Faker
     */
    private function generateFakerImage($faker, $courseData): ?string
    {
        try {
            $width = 800;
            $height = 600;
            
            // Use Lorem Picsum with random seed
            $imageUrl = "https://picsum.photos/{$width}/{$height}?random=" . rand(1, 1000);
            
            $imageContent = Http::timeout(30)->get($imageUrl)->body();
            $filename = 'courses/' . Str::slug($courseData['code']) . '.jpg';
            
            Storage::disk('public')->put($filename, $imageContent);
            
            return $filename;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Generate image using Picsum with seed based on course
     */
    private function generatePicsumImage($courseData): ?string
    {
        try {
            $width = 800;
            $height = 600;
            $seed = crc32($courseData['code']); // Generate consistent seed from course code
            
            $imageUrl = "https://picsum.photos/seed/{$seed}/{$width}/{$height}";
            
            $imageContent = Http::timeout(30)->get($imageUrl)->body();
            $filename = 'courses/' . Str::slug($courseData['code']) . '.jpg';
            
            Storage::disk('public')->put($filename, $imageContent);
            
            return $filename;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Map course categories to appropriate image categories
     */
    private function mapCategoryToPicsum($category): string
    {
        $mapping = [
            'programming' => 'tech',
            'computer' => 'tech',
            'coding' => 'tech',
            'electronics' => 'tech',
            'algorithms' => 'abstract',
            'hardware' => 'tech',
            'mathematics' => 'abstract',
            'database' => 'tech',
            'web-development' => 'tech',
            'design' => 'abstract',
            'operating-systems' => 'tech',
            'software-engineering' => 'tech',
            'networking' => 'tech',
            'artificial-intelligence' => 'tech',
            'mobile' => 'tech',
            'ui-ux' => 'abstract',
            'machine-learning' => 'tech',
            'cybersecurity' => 'tech',
            'data-science' => 'abstract',
            'cloud' => 'tech',
            'graphics' => 'abstract',
            'distributed-systems' => 'tech',
            'project-management' => 'business',
            'research' => 'nature',
            'statistics' => 'abstract',
            'data-analysis' => 'abstract',
        ];

        return $mapping[$category] ?? 'tech';
    }
}