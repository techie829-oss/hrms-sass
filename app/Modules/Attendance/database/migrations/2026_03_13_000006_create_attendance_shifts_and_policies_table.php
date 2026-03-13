<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_shifts', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id')->index();
            $table->string('name'); // Morning, Evening, Night, Flexible
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedSmallInteger('grace_minutes')->default(15);
            $table->unsignedSmallInteger('half_day_hours')->default(4);
            $table->boolean('is_overnight')->default(false);
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['tenant_id', 'name']);
        });

        Schema::create('attendance_policies', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id')->index();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('late_mark_after_minutes')->default(15);
            $table->unsignedSmallInteger('early_leave_before_minutes')->default(30);
            $table->unsignedSmallInteger('min_hours_full_day')->default(8);
            $table->unsignedSmallInteger('min_hours_half_day')->default(4);
            $table->boolean('auto_deduct_leave')->default(false);
            $table->unsignedSmallInteger('max_late_allowed_per_month')->default(3);
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['tenant_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_policies');
        Schema::dropIfExists('attendance_shifts');
    }
};
