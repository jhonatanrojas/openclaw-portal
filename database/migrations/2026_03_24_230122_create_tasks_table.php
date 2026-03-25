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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed', 'blocked', 'cancelled'])->default('pending');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('type', ['feature', 'bug', 'improvement', 'maintenance', 'documentation', 'devops'])->default('feature');
            $table->enum('category', ['backend', 'frontend', 'devops', 'documentation', 'general'])->default('general');
            $table->integer('estimated_hours')->nullable();
            $table->integer('actual_hours')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('due_date')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->json('tags')->nullable();
            $table->json('dependencies')->nullable();
            $table->text('acceptance_criteria')->nullable();
            $table->decimal('complexity_score', 3, 2)->default(0.5);
            $table->decimal('urgency_score', 3, 2)->default(0.5);
            $table->decimal('importance_score', 3, 2)->default(0.5);
            $table->decimal('overall_priority', 3, 2)->storedAs('(complexity_score + urgency_score + importance_score) / 3');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'priority']);
            $table->index(['category', 'type']);
            $table->index(['assigned_to', 'status']);
            $table->index(['due_date', 'status']);
            $table->index('overall_priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
