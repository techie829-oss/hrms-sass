<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id')->index();
            $table->string('name'); // Casual, Sick, Earned, Maternity, Paternity, etc.
            $table->string('code', 10);
            $table->unique(['tenant_id', 'name']);
            $table->unique(['tenant_id', 'code']);
            $table->unsignedSmallInteger('max_days_per_year')->default(12);
            $table->boolean('is_paid')->default(true);
            $table->boolean('is_carry_forward')->default(false);
            $table->unsignedSmallInteger('max_carry_forward_days')->default(0);
            $table->boolean('is_half_day_allowed')->default(true);
            $table->boolean('is_negative_balance_allowed')->default(false);
            $table->boolean('requires_approval')->default(true);
            $table->unsignedSmallInteger('min_days_notice')->default(0);
            $table->enum('applicable_gender', ['all', 'male', 'female'])->default('all');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_types');
    }
};
