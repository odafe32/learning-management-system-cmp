<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence(3, false);
        $departments = ['CSC'];
        $levels = ['100', '200', '300', '400', '500'];
        $semesters = ['first', 'second'];
        $statuses = ['active', 'inactive', 'draft'];

        return [
            'user_id' => User::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'code' => $this->faker->randomElement($departments) . $this->faker->numberBetween(100, 599),
            'description' => $this->faker->paragraph(3),
            'level' => $this->faker->randomElement($levels),
            'semester' => $this->faker->randomElement($semesters),
            'status' => $this->faker->randomElement($statuses),
            'credit_units' => $this->faker->numberBetween(1, 6),
            'image' => null,
        ];
    }

    /**
     * Indicate that the course is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the course is a draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
        ]);
    }

    /**
     * Indicate that the course is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }

    /**
     * Assign course to a specific lecturer.
     */
    public function forLecturer(User $lecturer): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $lecturer->id,
        ]);
    }

    /**
     * Set specific level.
     */
    public function level(string $level): static
    {
        return $this->state(fn (array $attributes) => [
            'level' => $level,
        ]);
    }
}