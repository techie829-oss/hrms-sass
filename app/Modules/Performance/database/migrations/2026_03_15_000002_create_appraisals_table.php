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
        Schema::create('appraisals', function (Blueprint $row) {
            $row->id();
            $row->string('tenant_id')->index();
            $row->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $row->foreignId('evaluator_id')->constrained('employees')->onDelete('cascade');
            $row->string('review_period');
            $row->decimal('score', 5, 2)->nullable();
            $row->text('comments')->nullable();
            $row->enum('status', ['draft', 'pending', 'completed'])->default('draft');
            $row->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appraisals');
    }
};
