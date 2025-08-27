<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Message;
use App\Models\User;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some users for testing
        $admin = User::where('role', 'admin')->first();
        $instructors = User::where('role', 'instructor')->take(2)->get();
        $students = User::where('role', 'student')->take(5)->get();

        if (!$admin || $instructors->isEmpty() || $students->isEmpty()) {
            $this->command->info('Skipping message seeding - not enough users found');
            return;
        }

        $messages = [
            // Admin to Instructor messages
            [
                'sender_id' => $admin->id,
                'receiver_id' => $instructors->first()->id,
                'receiver_role' => 'instructor',
                'content' => 'Welcome to the LMS platform! Please let me know if you need any assistance setting up your courses.',
                'is_read' => true,
                'created_at' => now()->subDays(5),
            ],
            [
                'sender_id' => $instructors->first()->id,
                'receiver_id' => $admin->id,
                'receiver_role' => 'admin',
                'content' => 'Thank you for the welcome message. I have a question about uploading course materials.',
                'is_read' => false,
                'created_at' => now()->subDays(4),
            ],

            // Student to Instructor messages
            [
                'sender_id' => $students->first()->id,
                'receiver_id' => $instructors->first()->id,
                'receiver_role' => 'instructor',
                'content' => 'Hello Professor, I have a question about the assignment deadline. Could you please clarify?',
                'is_read' => false,
                'created_at' => now()->subDays(2),
            ],
            [
                'sender_id' => $instructors->first()->id,
                'receiver_id' => $students->first()->id,
                'receiver_role' => 'student',
                'content' => 'Hi! The assignment deadline is next Friday at 11:59 PM. Make sure to submit it on time.',
                'is_read' => true,
                'created_at' => now()->subDays(1),
            ],

            // More student messages
            [
                'sender_id' => $students->get(1)->id,
                'receiver_id' => $instructors->first()->id,
                'receiver_role' => 'instructor',
                'content' => 'I\'m having trouble accessing the course materials. Can you help?',
                'is_read' => false,
                'created_at' => now()->subHours(6),
            ],
            [
                'sender_id' => $students->get(2)->id,
                'receiver_id' => $instructors->first()->id,
                'receiver_role' => 'instructor',
                'content' => 'Thank you for the excellent lecture today. The examples were very helpful!',
                'is_read' => false,
                'created_at' => now()->subHours(2),
            ],
        ];

        foreach ($messages as $messageData) {
            Message::create($messageData);
        }

        $this->command->info('Messages seeded successfully!');
    }
}