<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payroll_runs', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id')->index();
            $table->string('title'); // "March 2026 Payroll"
            $table->unsignedSmallInteger('month');
            $table->unsignedSmallInteger('year');
            $table->date('pay_date');
            $table->enum('status', ['draft', 'processing', 'completed', 'cancelled'])->default('draft');
            $table->unsignedInteger('total_employees')->default(0);
            $table->decimal('total_gross', 14, 2)->default(0);
            $table->decimal('total_deductions', 14, 2)->default(0);
            $table->decimal('total_net', 14, 2)->default(0);
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'month', 'year']);
        });

        Schema::create('payslips', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id')->index();
            $table->foreignId('payroll_run_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('payslip_number');
            $table->unsignedSmallInteger('month');
            $table->unsignedSmallInteger('year');

            // Attendance summary
            $table->unsignedSmallInteger('working_days')->default(0);
            $table->unsignedSmallInteger('present_days')->default(0);
            $table->unsignedSmallInteger('absent_days')->default(0);
            $table->unsignedSmallInteger('leave_days')->default(0);
            $table->unsignedSmallInteger('holidays')->default(0);

            // Salary breakdown
            $table->decimal('basic_salary', 12, 2)->default(0);
            $table->decimal('gross_earnings', 12, 2)->default(0);
            $table->decimal('total_deductions', 12, 2)->default(0);
            $table->decimal('net_salary', 12, 2)->default(0);
            $table->json('earnings_breakdown'); // [{component, amount}]
            $table->json('deductions_breakdown'); // [{component, amount}]

            // Status
            $table->enum('status', ['draft', 'generated', 'paid', 'cancelled'])->default('draft');
            $table->date('payment_date')->nullable();
            $table->string('payment_mode')->nullable(); // bank_transfer, cheque, cash
            $table->string('transaction_ref')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'payslip_number']);
            $table->unique(['tenant_id', 'employee_id', 'month', 'year']);
            $table->index(['tenant_id', 'month', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payslips');
        Schema::dropIfExists('payroll_runs');
    }
};
