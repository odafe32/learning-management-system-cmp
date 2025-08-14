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
        Schema::create('submissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('assignment_id');
            $table->uuid('student_id');
            $table->longText('code_content')->nullable();
            $table->string('file_path')->nullable();
            $table->enum('status', ['draft', 'submitted', 'pending', 'graded', 'returned'])->default('draft');
            $table->decimal('grade', 5, 2)->nullable();
            $table->text('feedback')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('graded_at')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('assignment_id')->references('id')->on('assignments')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');

            // Indexes
            $table->index(['assignment_id', 'student_id']);
            $table->index(['student_id', 'status']);
            $table->index(['assignment_id', 'status']);
            $table->index('submitted_at');
            $table->index('graded_at');

            // Unique constraint to prevent duplicate submissions
            $table->unique(['assignment_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};