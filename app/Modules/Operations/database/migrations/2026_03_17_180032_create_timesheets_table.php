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
        if (!Schema::hasTable('timesheets')) {
            Schema::create('timesheets', function (Blueprint $table) {
                $table->id();
                $table->string('tenant_id')->index();
                $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
                $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
                $table->foreignId('task_id')->nullable()->constrained('tasks')->onDelete('set null');
                $table->date('date');
                $table->decimal('hours', 4, 2);
                $table->text('description')->nullable();
                $table->string('status')->default('submitted'); // draft, submitted, approved, rejected
                $table->foreignId('approved_by')->nullable()->constrained('employees')->onDelete('set null');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timesheets');
    }
};
