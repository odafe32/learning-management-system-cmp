<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting database seeding...');
        
        // Seed in the correct order to maintain referential integrity
        $this->call([
            UserSeeder::class,       // First: Create users (admins, instructors, students)
            CourseSeeder::class,     // Second: Create courses (requires instructors)
            MaterialSeeder::class,   // Third: Create materials (requires courses and instructors)
            AssignmentSeeder::class, // Fourth: Create assignments (requires courses and instructors)
            SubmissionSeeder::class, // Fifth: Create submissions (requires assignments and students)
            CourseStudentSeeder::class, // Fifth: Create submissions (requires assignments and students)
            MessageSeeder::class, // Fifth: Create submissions (requires assignments and students)
        ]);

        $this->command->info('🎉 Database seeding completed successfully!');
        $this->command->info('');
        $this->command->info('📊 Summary:');
        $this->command->info('- Users: ' . \App\Models\User::count());
        $this->command->info('- Courses: ' . \App\Models\Course::count());
        $this->command->info('- Materials: ' . \App\Models\Material::count());
        $this->command->info('- Assignments: ' . \App\Models\Assignment::count());
        $this->command->info('- Submissions: ' . \App\Models\Submission::count());
    }
}