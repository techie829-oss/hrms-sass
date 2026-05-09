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
        if (!Schema::hasTable('attendance_role_enforcements')) {
            Schema::create('attendance_role_enforcements', function (Blueprint $table) {
                $table->id();
                $table->string('tenant_id')->index();
                $table->unsignedBigInteger('role_id');
                $table->integer('enforce_kiosk')->default(0); // 0=Inherit, 1=Force, 2=Exempt
                $table->integer('multi_clocking')->default(0); // 0=Inherit, 1=Allow, 2=Disallow
                $table->timestamps();

                $table->unique(['tenant_id', 'role_id']);
            });
        }

        if (!Schema::hasTable('attendance_employee_enforcements')) {
            Schema::create('attendance_employee_enforcements', function (Blueprint $table) {
                $table->id();
                $table->string('tenant_id')->index();
                $table->unsignedBigInteger('employee_id');
                $table->integer('enforce_kiosk')->default(0); // 0=Inherit, 1=Force, 2=Exempt
                $table->integer('multi_clocking')->default(0); // 0=Inherit, 1=Allow, 2=Disallow
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
    }
};
