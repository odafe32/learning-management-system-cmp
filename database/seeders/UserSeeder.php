<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
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
            'email_verified_at' => now(),
        ]);

        // Create Only 2 Instructor Users
        User::create([
            'name' => 'Dr. John Smith',
            'email' => 'instructor@example.com',
            'password' => Hash::make('password'),
            'role' => 'instructor',
            'phone' => '+1234567891',
            'gender' => 'male',
            'matric_or_staff_id' => 'INS001',
            'department' => 'Computer Science',
            'faculty' => 'Natural and Applied Science',
            'address' => '456 Faculty Avenue, City',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Prof. Sarah Johnson',
            'email' => 'sarah.johnson@example.com',
            'password' => Hash::make('password'),
            'role' => 'instructor',
            'phone' => '+1234567892',
            'gender' => 'female',
            'matric_or_staff_id' => 'INS002',
            'department' => 'Computer Science',
            'faculty' => 'Natural and Applied Science',
            'address' => '789 Science Road, City',
            'email_verified_at' => now(),
        ]);

        // Create Student Users
        User::create([
            'name' => 'Odafe Godfrey',
            'email' => 'godfreyj.sule@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'phone' => '+1234567893',
            'gender' => 'female',
            'matric_or_staff_id' => 'STU001',
            'department' => 'Computer Science',
            'faculty' => 'Natural and Applied Science',
            'level' => '300',
            'address' => '321 Student Lane, City',
            'birth_date' => '2002-05-15',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Bob Wilson',
            'email' => 'bob.wilson@example.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'phone' => '+1234567894',
            'gender' => 'male',
            'matric_or_staff_id' => 'STU002',
            'department' => 'Computer Science',
            'faculty' => 'Natural and Applied Science',
            'level' => '200',
            'address' => '654 Campus Drive, City',
            'birth_date' => '2003-08-22',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Emma Davis',
            'email' => 'emma.davis@example.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'phone' => '+1234567895',
            'gender' => 'female',
            'matric_or_staff_id' => 'STU003',
            'department' => 'Computer Science',
            'faculty' => 'Natural and Applied Science',
            'level' => '400',
            'address' => '987 University Street, City',
            'birth_date' => '2001-12-10',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Charlie Green',
            'email' => 'charlie.green@example.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'phone' => '+1234567898',
            'gender' => 'male',
            'matric_or_staff_id' => 'STU004',
            'department' => 'Computer Science',
            'faculty' => 'Natural and Applied Science',
            'level' => '100',
            'address' => '111 Freshman Hall, City',
            'birth_date' => '2004-03-20',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Diana White',
            'email' => 'diana.white@example.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'phone' => '+1234567899',
            'gender' => 'female',
            'matric_or_staff_id' => 'STU005',
            'department' => 'Computer Science',
            'faculty' => 'Natural and Applied Science',
            'level' => '200',
            'address' => '222 Sophomore Dorm, City',
            'birth_date' => '2003-11-15',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Frank Miller',
            'email' => 'frank.miller@example.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'phone' => '+1234567800',
            'gender' => 'male',
            'matric_or_staff_id' => 'STU006',
            'department' => 'Computer Science',
            'faculty' => 'Natural and Applied Science',
            'level' => '300',
            'address' => '333 Junior Hall, City',
            'birth_date' => '2002-07-08',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Grace Taylor',
            'email' => 'grace.taylor@example.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'phone' => '+1234567801',
            'gender' => 'female',
            'matric_or_staff_id' => 'STU007',
            'department' => 'Computer Science',
            'faculty' => 'Natural and Applied Science',
            'level' => '100',
            'address' => '444 Business Dorm, City',
            'birth_date' => '2004-01-25',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Henry Clark',
            'email' => 'henry.clark@example.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'phone' => '+1234567802',
            'gender' => 'male',
            'matric_or_staff_id' => 'STU008',
            'department' => 'Computer Science',
            'faculty' => 'Natural and Applied Science',
            'level' => '400',
            'address' => '555 Senior Hall, City',
            'birth_date' => '2001-09-12',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Ivy Rodriguez',
            'email' => 'ivy.rodriguez@example.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'phone' => '+1234567803',
            'gender' => 'female',
            'matric_or_staff_id' => 'STU009',
            'department' => 'Computer Science',
            'faculty' => 'Natural and Applied Science',
            'level' => '300',
            'address' => '666 Tech Hall, City',
            'birth_date' => '2002-04-18',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Jack Anderson',
            'email' => 'jack.anderson@example.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'phone' => '+1234567804',
            'gender' => 'male',
            'matric_or_staff_id' => 'STU010',
            'department' => 'Computer Science',
            'faculty' => 'Natural and Applied Science',
            'level' => '200',
            'address' => '777 Math Building, City',
            'birth_date' => '2003-06-30',
            'email_verified_at' => now(),
        ]);

        // Create additional random students for testing (only students, no more instructors)
        User::factory(10)->create([
            'role' => 'student',
            'department' => 'Computer Science',
            'faculty' => 'Natural and Applied Science',
            'level' => fake()->randomElement(['100', '200', '300', '400']),
        ]);

        $this->command->info('Users seeded successfully!');
        $this->command->info('Created: 1 Admin, 2 Instructors, 20 Students');
        $this->command->info('All users assigned to Computer Science department in Natural and Applied Science faculty');
    }
}