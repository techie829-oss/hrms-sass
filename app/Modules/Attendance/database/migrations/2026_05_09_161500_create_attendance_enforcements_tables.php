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
        if (Schema::hasTable('attendance_policies') && !Schema::hasColumn('attendance_policies', 'enforce_clockin')) {
            Schema::table('attendance_policies', function (Blueprint $table) {
                $table->boolean('enforce_clockin')->default(false)->after('is_active');
            });
        }

        if (!Schema::hasTable('attendance_role_enforcements')) {
            Schema::create('attendance_role_enforcements', function (Blueprint $table) {
                $table->id();
                $table->string('tenant_id')->index();
                $table->string('role_name');
                $table->boolean('checkin_required')->default(true);
                $table->timestamps();

                $table->unique(['tenant_id', 'role_name']);
            });
        }

        if (!Schema::hasTable('attendance_employee_enforcements')) {
            Schema::create('attendance_employee_enforcements', function (Blueprint $table) {
                $table->id();
                $table->string('tenant_id')->index();
                $table->unsignedBigInteger('employee_id');
                $table->boolean('checkin_required')->default(true); // true = forced, false = exempt/bypass
                $table->timestamps();

                $table->unique(['tenant_id', 'employee_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_employee_enforcements');
        Schema::dropIfExists('attendance_role_enforcements');

        if (Schema::hasTable('attendance_policies') && Schema::hasColumn('attendance_policies', 'enforce_clockin')) {
            Schema::table('attendance_policies', function (Blueprint $table) {
                $table->dropColumn('enforce_clockin');
            });
        }
    }
};
