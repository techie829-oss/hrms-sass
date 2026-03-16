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
        Schema::create('goals', function (Blueprint $row) {
            $row->id();
            $row->string('tenant_id')->index();
            $row->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $row->string('title');
            $row->text('description')->nullable();
            $row->date('start_date');
            $row->date('end_date');
            $row->integer('progress_percentage')->default(0);
            $row->enum('status', ['in_progress', 'completed', 'cancelled'])->default('in_progress');
            $row->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goals');
    }
};
