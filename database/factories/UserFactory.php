<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => fake()->randomElement(User::getRoles()),
            'phone' => fake()->phoneNumber(),
            'gender' => fake()->randomElement(['male', 'female']),
            'matric_or_staff_id' => fake()->unique()->regexify('[A-Z]{3}[0-9]{3}'),
            'department' => fake()->randomElement([
                'Computer Science',
                'Mathematics',
                'Physics',
                'Chemistry',
                'Biology',
                'Business Administration',
                'Economics',
                'Psychology',
                'English Literature',
                'History'
            ]),
            'faculty' => fake()->randomElement([
                'Engineering',
                'Science',
                'Management',
                'Arts',
                'Social Sciences'
            ]),
            'level' => fake()->randomElement(['100', '200', '300', '400']),
            'address' => fake()->address(),
            'birth_date' => fake()->dateTimeBetween('-30 years', '-18 years')->format('Y-m-d'),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Create a student user.
     */
    public function student(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => User::ROLE_STUDENT,
            'level' => fake()->randomElement(['100', '200', '300', '400']),
        ]);
    }

    /**
     * Create a lecturer user.
     */
    public function lecturer(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => User::ROLE_LECTURER,
            'level' => null,
        ]);
    }

    /**
     * Create an admin user.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => User::ROLE_ADMIN,
            'level' => null,
            'department' => 'Administration',
        ]);
    }
}