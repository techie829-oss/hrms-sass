<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id')->index();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('employee_id', 20);
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('designation_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedBigInteger('reporting_to')->nullable();

            // Personal Info
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');

            $table->unique(['tenant_id', 'employee_id']);
            $table->unique(['tenant_id', 'email']);
            $table->string('phone', 20)->nullable();
            $table->string('alt_phone', 20)->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->date('date_of_birth')->nullable();
            $table->text('current_address')->nullable();
            $table->text('permanent_address')->nullable();

            // Employment Info
            $table->date('date_of_joining');
            $table->date('date_of_leaving')->nullable();
            $table->date('probation_end_date')->nullable();
            $table->enum('employment_type', ['full_time', 'part_time', 'contract', 'intern'])->default('full_time');
            $table->enum('status', ['active', 'inactive', 'on_leave', 'terminated', 'resigned'])->default('active');
            $table->string('work_location')->nullable();

            // Salary
            $table->decimal('basic_salary', 12, 2)->default(0);
            $table->string('salary_currency', 3)->default('INR');
            $table->enum('pay_frequency', ['monthly', 'bi_weekly', 'weekly'])->default('monthly');

            // Identity
            $table->string('pan_number', 20)->nullable();
            $table->string('aadhar_number', 20)->nullable();
            $table->string('passport_number', 20)->nullable();

            // Emergency
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone', 20)->nullable();
            $table->string('emergency_contact_relation')->nullable();

            // Meta
            $table->string('profile_photo')->nullable();
            $table->json('custom_fields')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('reporting_to')->references('id')->on('employees')->nullOnDelete();
        });

        // Add deferred FK for department head
        Schema::table('departments', function (Blueprint $table) {
            $table->foreign('head_employee_id')->references('id')->on('employees')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropForeign(['head_employee_id']);
        });
        Schema::dropIfExists('employees');
    }
};
