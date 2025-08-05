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
            'role' => User::ROLE_ADMIN,
            'phone' => '+1234567890',
            'gender' => 'male',
            'matric_or_staff_id' => 'ADM001',
            'department' => 'Administration',
            'faculty' => 'Management',
            'address' => '123 Admin Street, City',
            'email_verified_at' => now(),
        ]);

        // Create Lecturer Users
        User::create([
            'name' => 'Dr. John Smith',
            'email' => 'lecturer@example.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_LECTURER,
            'phone' => '+1234567891',
            'gender' => 'male',
            'matric_or_staff_id' => 'LEC001',
            'department' => 'Computer Science',
            'faculty' => 'Engineering',
            'address' => '456 Faculty Avenue, City',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Prof. Sarah Johnson',
            'email' => 'sarah.johnson@example.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_LECTURER,
            'phone' => '+1234567892',
            'gender' => 'female',
            'matric_or_staff_id' => 'LEC002',
            'department' => 'Mathematics',
            'faculty' => 'Science',
            'address' => '789 Science Road, City',
            'email_verified_at' => now(),
        ]);

        // Create Student Users
        User::create([
            'name' => 'Alice Brown',
            'email' => 'student@example.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_STUDENT,
            'phone' => '+1234567893',
            'gender' => 'female',
            'matric_or_staff_id' => 'STU001',
            'department' => 'Computer Science',
            'faculty' => 'Engineering',
            'level' => '300',
            'address' => '321 Student Lane, City',
            'birth_date' => '2002-05-15',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Bob Wilson',
            'email' => 'bob.wilson@example.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_STUDENT,
            'phone' => '+1234567894',
            'gender' => 'male',
            'matric_or_staff_id' => 'STU002',
            'department' => 'Mathematics',
            'faculty' => 'Science',
            'level' => '200',
            'address' => '654 Campus Drive, City',
            'birth_date' => '2003-08-22',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Emma Davis',
            'email' => 'emma.davis@example.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_STUDENT,
            'phone' => '+1234567895',
            'gender' => 'female',
            'matric_or_staff_id' => 'STU003',
            'department' => 'Business Administration',
            'faculty' => 'Management',
            'level' => '400',
            'address' => '987 University Street, City',
            'birth_date' => '2001-12-10',
            'email_verified_at' => now(),
        ]);

        // Create additional random users for testing
        User::factory(10)->create([
            'role' => User::ROLE_STUDENT,
            'level' => fake()->randomElement(['100', '200', '300', '400']),
        ]);

        User::factory(5)->create([
            'role' => User::ROLE_LECTURER,
        ]);
    }
}