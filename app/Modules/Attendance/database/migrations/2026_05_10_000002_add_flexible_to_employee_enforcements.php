<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add shift-specific min_hours_full_day to attendance_shifts.
     * Different shifts can have different full-day thresholds:
     *   e.g. Night Shift = 7h, Morning Shift = 8h, Part-Time = 5h
     * If null, falls back to AttendancePolicy->min_hours_full_day.
     *
     * Also add per-employee is_flexible to attendance_employee_enforcements:
     *   Flexible employee = only min_hours count, no late/early leave penalty.
     */
    public function up(): void
    {
        Schema::table('attendance_shifts', function (Blueprint $table) {
            $table->unsignedSmallInteger('min_hours_full_day')
                  ->nullable()
                  ->after('half_day_hours')
                  ->comment('Override policy default. Null = use policy value.');
        });

        Schema::table('attendance_employee_enforcements', function (Blueprint $table) {
            $table->boolean('is_flexible')
                  ->default(false)
                  ->after('multi_clocking')
                  ->comment('If true: only min_hours matter, no late/early leave based on clock time.');
        });
    }

    public function down(): void
    {
        Schema::table('attendance_shifts', function (Blueprint $table) {
            $table->dropColumn('min_hours_full_day');
        });
        Schema::table('attendance_employee_enforcements', function (Blueprint $table) {
            $table->dropColumn('is_flexible');
        });
    }
};
