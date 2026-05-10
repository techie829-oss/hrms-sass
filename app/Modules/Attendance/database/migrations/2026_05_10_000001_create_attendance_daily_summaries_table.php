<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create the attendance_daily_summaries table.
     * This table stores ONE computed summary row per employee per date.
     * It is re-calculated every time a clock-in or clock-out happens.
     *
     * Separate from attendance_logs (raw punch data) to support multi-clocking.
     */
    public function up(): void
    {
        Schema::create('attendance_daily_summaries', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id')->index();
            $table->unsignedBigInteger('employee_id');
            $table->date('date')->index();

            // --- Computed Times ---
            $table->dateTime('first_check_in')->nullable();
            $table->dateTime('last_check_out')->nullable();

            // --- Calculated Totals ---
            $table->decimal('total_worked_hours', 5, 2)->default(0);
            $table->unsignedSmallInteger('total_sessions')->default(0); // no. of punch pairs

            // --- Day Classification ---
            $table->enum('day_type', ['full_day', 'half_day', 'quarter_day', 'absent', 'weekend', 'holiday'])
                  ->default('absent');

            // --- Smart Tags (array stored as JSON) ---
            // Possible values: 'late_arrived', 'checkout_missing', 'overtime', 'early_leave', 'multi_session'
            $table->json('tags')->nullable();

            // --- Numeric metrics ---
            $table->unsignedSmallInteger('late_minutes')->default(0);
            $table->unsignedSmallInteger('overtime_minutes')->default(0);
            $table->unsignedSmallInteger('early_leave_minutes')->default(0);

            // --- Status ---
            $table->enum('status', ['present', 'late', 'half_day', 'absent', 'weekend', 'holiday'])
                  ->default('absent');

            $table->timestamps();

            // One summary per employee per day per tenant
            $table->unique(['tenant_id', 'employee_id', 'date']);
            $table->index(['tenant_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_daily_summaries');
    }
};
