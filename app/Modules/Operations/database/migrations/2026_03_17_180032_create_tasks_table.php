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
        if (!Schema::hasTable('tasks')) {
            Schema::create('tasks', function (Blueprint $table) {
                $table->id();
                $table->string('tenant_id')->index();
                $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
                $table->foreignId('assigned_to')->nullable()->constrained('employees')->onDelete('set null');
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('priority')->default('medium'); // low, medium, high, urgent
                $table->string('status')->default('todo'); // todo, in_progress, review, done
                $table->date('due_date')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
