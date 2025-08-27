<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            
            // Who sent the message
            $table->foreignUuid('sender_id')->constrained('users')->onDelete('cascade');
            
            // Who is receiving the message
            $table->foreignUuid('receiver_id')->constrained('users')->onDelete('cascade');
            
            // Optional: Category or group (like admin broadcast)
            $table->string('receiver_role')->nullable(); // e.g., 'student', 'instructor', 'admin'
            
            // Message content
            $table->text('content');
            $table->string('attachment')->nullable(); // optional file path
            
            // Read status
            $table->boolean('is_read')->default(false);
            
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['receiver_id', 'is_read']);
            $table->index(['sender_id', 'receiver_id']);
            $table->index(['receiver_role']);
            $table->index(['created_at']);
            
            // Composite index for conversations
            $table->index(['sender_id', 'receiver_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};