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
        Schema::create('course_student', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade'); // student
            $table->foreignUuid('course_id')->constrained('courses')->onDelete('cascade'); // course - fixed to UUID
            $table->timestamp('enrolled_at')->useCurrent();
            $table->enum('status', ['active', 'inactive', 'completed', 'dropped'])->default('active');
            $table->timestamps();
            
            // Prevent duplicate enrollment
            $table->unique(['user_id', 'course_id']);
            
            // Add indexes for better performance
            $table->index(['course_id', 'status']);
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_student');
    }
};