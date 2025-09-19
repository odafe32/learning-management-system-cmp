<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Create users directory if it doesn't exist
        if (!Storage::disk('public')->exists('users')) {
            Storage::disk('public')->makeDirectory('users');
        }

        $imagesDownloaded = 0;
        $imagesCached = 0;

        // Create Admin User
        $adminImageResult = $this->generateUserImage($faker, [
            'name' => 'Admin User',
            'role' => 'admin',
            'gender' => 'male',
            'keywords' => ['business', 'professional', 'executive', 'manager']
        ]);

        if ($adminImageResult['cached']) {
            $imagesCached++;
        } elseif ($adminImageResult['path']) {
            $imagesDownloaded++;
        }

        User::create([
            'name' => 'Admin User',
            'email' => 'godfreyj.sule1@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '+1234567890',
            'gender' => 'male',
            'matric_or_staff_id' => 'ADM001',
            'department' => 'Computer Science',
            'faculty' => 'Natural and Applied Science',
            'address' => '123 Admin Street, City',
            'avatar' => $adminImageResult['path'],
            'email_verified_at' => now(),
        ]);

        // Create Instructor Users
        $instructors = [
            [
                'name' => 'Dr. John Smith',
                'email' => 'instructor@example.com',
                'gender' => 'male',
                'matric_or_staff_id' => 'INS001',
                'address' => '456 Faculty Avenue, City',
                'phone' => '+1234567891',
                'keywords' => ['professor', 'teacher', 'academic', 'educator']
            ],
            [
                'name' => 'Prof. Sarah Johnson',
                'email' => 'sarah.johnson@example.com',
                'gender' => 'female',
                'matric_or_staff_id' => 'INS002',
                'address' => '789 Science Road, City',
                'phone' => '+1234567892',
                'keywords' => ['professor', 'teacher', 'academic', 'educator']
            ]
        ];

        foreach ($instructors as $instructorData) {
            $imageResult = $this->generateUserImage($faker, [
                'name' => $instructorData['name'],
                'role' => 'instructor',
                'gender' => $instructorData['gender'],
                'keywords' => $instructorData['keywords']
            ]);

            if ($imageResult['cached']) {
                $imagesCached++;
            } elseif ($imageResult['path']) {
                $imagesDownloaded++;
            }

            User::create([
                'name' => $instructorData['name'],
                'email' => $instructorData['email'],
                'password' => Hash::make('password'),
                'role' => 'instructor',
                'phone' => $instructorData['phone'],
                'gender' => $instructorData['gender'],
                'matric_or_staff_id' => $instructorData['matric_or_staff_id'],
                'department' => 'Computer Science',
                'faculty' => 'Natural and Applied Science',
                'address' => $instructorData['address'],
                'avatar' => $imageResult['path'],
                'email_verified_at' => now(),
            ]);
        }

        // Create Student Users
        $students = [
            [
                'name' => 'Odafe Godfrey',
                'email' => 'godfreyj.sule@gmail.com',
                'gender' => 'male', // Fixed gender
                'matric_or_staff_id' => 'STU001',
                'level' => '300',
                'address' => '321 Student Lane, City',
                'birth_date' => '2002-05-15',
                'phone' => '+1234567893',
            ],
            [
                'name' => 'Bob Wilson',
                'email' => 'bob.wilson@example.com',
                'gender' => 'male',
                'matric_or_staff_id' => 'STU002',
                'level' => '200',
                'address' => '654 Campus Drive, City',
                'birth_date' => '2003-08-22',
                'phone' => '+1234567894',
            ],
            [
                'name' => 'Emma Davis',
                'email' => 'emma.davis@example.com',
                'gender' => 'female',
                'matric_or_staff_id' => 'STU003',
                'level' => '400',
                'address' => '987 University Street, City',
                'birth_date' => '2001-12-10',
                'phone' => '+1234567895',
            ],
            [
                'name' => 'Charlie Green',
                'email' => 'charlie.green@example.com',
                'gender' => 'male',
                'matric_or_staff_id' => 'STU004',
                'level' => '100',
                'address' => '111 Freshman Hall, City',
                'birth_date' => '2004-03-20',
                'phone' => '+1234567898',
            ],
            [
                'name' => 'Diana White',
                'email' => 'diana.white@example.com',
                'gender' => 'female',
                'matric_or_staff_id' => 'STU005',
                'level' => '200',
                'address' => '222 Sophomore Dorm, City',
                'birth_date' => '2003-11-15',
                'phone' => '+1234567899',
            ],
            [
                'name' => 'Frank Miller',
                'email' => 'frank.miller@example.com',
                'gender' => 'male',
                'matric_or_staff_id' => 'STU006',
                'level' => '300',
                'address' => '333 Junior Hall, City',
                'birth_date' => '2002-07-08',
                'phone' => '+1234567800',
            ],
            [
                'name' => 'Grace Taylor',
                'email' => 'grace.taylor@example.com',
                'gender' => 'female',
                'matric_or_staff_id' => 'STU007',
                'level' => '100',
                'address' => '444 Business Dorm, City',
                'birth_date' => '2004-01-25',
                'phone' => '+1234567801',
            ],
            [
                'name' => 'Henry Clark',
                'email' => 'henry.clark@example.com',
                'gender' => 'male',
                'matric_or_staff_id' => 'STU008',
                'level' => '400',
                'address' => '555 Senior Hall, City',
                'birth_date' => '2001-09-12',
                'phone' => '+1234567802',
            ],
            [
                'name' => 'Ivy Rodriguez',
                'email' => 'ivy.rodriguez@example.com',
                'gender' => 'female',
                'matric_or_staff_id' => 'STU009',
                'level' => '300',
                'address' => '666 Tech Hall, City',
                'birth_date' => '2002-04-18',
                'phone' => '+1234567803',
            ],
            [
                'name' => 'Jack Anderson',
                'email' => 'jack.anderson@example.com',
                'gender' => 'male',
                'matric_or_staff_id' => 'STU010',
                'level' => '200',
                'address' => '777 Math Building, City',
                'birth_date' => '2003-06-30',
                'phone' => '+1234567804',
            ],
        ];

        foreach ($students as $studentData) {
            $imageResult = $this->generateUserImage($faker, [
                'name' => $studentData['name'],
                'role' => 'student',
                'gender' => $studentData['gender'],
                'keywords' => ['student', 'young', 'university', 'college']
            ]);

            if ($imageResult['cached']) {
                $imagesCached++;
            } elseif ($imageResult['path']) {
                $imagesDownloaded++;
            }

            User::create([
                'name' => $studentData['name'],
                'email' => $studentData['email'],
                'password' => Hash::make('password'),
                'role' => 'student',
                'phone' => $studentData['phone'],
                'gender' => $studentData['gender'],
                'matric_or_staff_id' => $studentData['matric_or_staff_id'],
                'department' => 'Computer Science',
                'faculty' => 'Natural and Applied Science',
                'level' => $studentData['level'],
                'address' => $studentData['address'],
                'birth_date' => $studentData['birth_date'],
                'avatar' => $imageResult['path'],
                'email_verified_at' => now(),
            ]);
        }

        // Create additional random students using factory
        $factoryStudents = User::factory(10)->make([
            'role' => 'student',
            'department' => 'Computer Science',
            'faculty' => 'Natural and Applied Science',
            'level' => fake()->randomElement(['100', '200', '300', '400']),
        ]);

        foreach ($factoryStudents as $student) {
            $imageResult = $this->generateUserImage($faker, [
                'name' => $student->name,
                'role' => 'student',
                'gender' => $student->gender,
                'keywords' => ['student', 'young', 'university', 'college']
            ]);

            if ($imageResult['cached']) {
                $imagesCached++;
            } elseif ($imageResult['path']) {
                $imagesDownloaded++;
            }

            $student->avatar = $imageResult['path'];
            $student->save();
        }

        $this->command->info('Users seeded successfully!');
        $this->command->info('Created: 1 Admin, 2 Instructors, 20 Students');
        $this->command->info("Profile Images: {$imagesCached} cached, {$imagesDownloaded} downloaded");
        $this->command->info('All users assigned to Computer Science department in Natural and Applied Science faculty');
    }

    /**
     * Check if user image already exists in storage
     */
    private function checkExistingUserImage($userData): ?string
    {
        $userSlug = Str::slug($userData['name']);
        $possibleExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        
        foreach ($possibleExtensions as $ext) {
            $filename = "users/{$userSlug}.{$ext}";
            if (Storage::disk('public')->exists($filename)) {
                return $filename;
            }
            
            // Also check with pattern that includes role or ID
            $files = Storage::disk('public')->files('users');
            foreach ($files as $file) {
                if (str_contains($file, $userSlug) && str_ends_with($file, ".{$ext}")) {
                    return $file;
                }
            }
        }
        
        return null;
    }

    /**
     * Generate user profile image using Faker and Unsplash with caching
     */
    private function generateUserImage($faker, $userData): array
    {
        // First, check if image already exists
        $existingImage = $this->checkExistingUserImage($userData);
        if ($existingImage) {
            $this->command->info("Using cached image for {$userData['name']}: {$existingImage}");
            return [
                'path' => $existingImage,
                'cached' => true
            ];
        }

        try {
            // Method 1: Use Unsplash API (recommended)
            $imagePath = $this->downloadUnsplashUserImage($userData);
            if ($imagePath) {
                $this->command->info("Downloaded Unsplash image for {$userData['name']}: {$imagePath}");
                return [
                    'path' => $imagePath,
                    'cached' => false
                ];
            }

            // Method 2: Use Lorem Picsum with person category
            $imagePath = $this->generateFakerUserImage($faker, $userData);
            if ($imagePath) {
                $this->command->info("Generated Faker image for {$userData['name']}: {$imagePath}");
                return [
                    'path' => $imagePath,
                    'cached' => false
                ];
            }

            // Method 3: Use Picsum with seed based on user name
            $imagePath = $this->generatePicsumUserImage($userData);
            if ($imagePath) {
                $this->command->info("Generated Picsum image for {$userData['name']}: {$imagePath}");
                return [
                    'path' => $imagePath,
                    'cached' => false
                ];
            }

        } catch (\Exception $e) {
            $this->command->warn("Failed to generate image for {$userData['name']}: " . $e->getMessage());
        }

        return [
            'path' => null,
            'cached' => false
        ];
    }

    /**
     * Download user image from Unsplash API
     */
    private function downloadUnsplashUserImage($userData): ?string
    {
        try {
            $unsplashAccessKey = env('UNSPLASH_ACCESS_KEY');
            
            if (!$unsplashAccessKey) {
                return null;
            }

            // Get appropriate search term based on role and gender
            $searchTerms = $this->getUnsplashSearchTerms($userData);
            $keyword = $searchTerms[array_rand($searchTerms)];
            
            $response = Http::timeout(30)->get('https://api.unsplash.com/photos/random', [
                'client_id' => $unsplashAccessKey,
                'query' => $keyword,
                'w' => 400,
                'h' => 400,
                'fit' => 'crop',
                'face' => 'true', // Focus on faces for profile pictures
            ]);

            if ($response->successful()) {
                $imageData = $response->json();
                $imageUrl = $imageData['urls']['regular'];
                
                // Download and save the image with consistent naming
                $imageContent = Http::timeout(30)->get($imageUrl)->body();
                $filename = 'users/' . Str::slug($userData['name']) . '.jpg';
                
                Storage::disk('public')->put($filename, $imageContent);
                
                return $filename;
            }
        } catch (\Exception $e) {
            // Fall back to other methods
        }

        return null;
    }

    /**
     * Generate user image using Faker with Lorem Picsum
     */
    private function generateFakerUserImage($faker, $userData): ?string
    {
        try {
            $width = 400;
            $height = 400;
            
            // Use Lorem Picsum with random seed for people photos
            $imageUrl = "https://picsum.photos/{$width}/{$height}?random=" . rand(1, 1000);
            
            $imageContent = Http::timeout(30)->get($imageUrl)->body();
            $filename = 'users/' . Str::slug($userData['name']) . '.jpg';
            
            Storage::disk('public')->put($filename, $imageContent);
            
            return $filename;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Generate user image using Picsum with seed based on user name
     */
    private function generatePicsumUserImage($userData): ?string
    {
        try {
            $width = 400;
            $height = 400;
            $seed = crc32($userData['name']); // Generate consistent seed from user name
            
            $imageUrl = "https://picsum.photos/seed/{$seed}/{$width}/{$height}";
            
            $imageContent = Http::timeout(30)->get($imageUrl)->body();
            $filename = 'users/' . Str::slug($userData['name']) . '.jpg';
            
            Storage::disk('public')->put($filename, $imageContent);
            
            return $filename;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get appropriate Unsplash search terms based on user data
     */
    private function getUnsplashSearchTerms($userData): array
    {
        $baseTerms = ['portrait', 'person', 'professional'];
        
        // Add gender-specific terms
        if ($userData['gender'] === 'male') {
            $baseTerms = array_merge($baseTerms, ['man', 'male']);
        } elseif ($userData['gender'] === 'female') {
            $baseTerms = array_merge($baseTerms, ['woman', 'female']);
        }
        
        // Add role-specific terms
        switch ($userData['role']) {
            case 'admin':
                $baseTerms = array_merge($baseTerms, ['executive', 'manager', 'business', 'professional']);
                break;
            case 'instructor':
                $baseTerms = array_merge($baseTerms, ['teacher', 'professor', 'academic', 'educator']);
                break;
            case 'student':
                $baseTerms = array_merge($baseTerms, ['student', 'young', 'university', 'college']);
                break;
        }
        
        // Add custom keywords if provided
        if (isset($userData['keywords'])) {
            $baseTerms = array_merge($baseTerms, $userData['keywords']);
        }
        
        return array_unique($baseTerms);
    }

    /**
     * Generate avatar using UI Avatars as fallback
     */
    private function generateUIAvatar($userData): ?string
    {
        try {
            $name = urlencode($userData['name']);
            $background = $this->getAvatarColor($userData['role']);
            $color = 'ffffff';
            
            $imageUrl = "https://ui-avatars.com/api/?name={$name}&size=400&background={$background}&color={$color}&format=png";
            
            $imageContent = Http::timeout(30)->get($imageUrl)->body();
            $filename = 'users/' . Str::slug($userData['name']) . '-avatar.png';
            
            Storage::disk('public')->put($filename, $imageContent);
            
            return $filename;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get avatar background color based on role
     */
    private function getAvatarColor($role): string
    {
        return match($role) {
            'admin' => 'dc3545',      // Red
            'instructor' => '0d6efd',  // Blue
            'student' => '198754',     // Green
            default => '6c757d',       // Gray
        };
    }
}