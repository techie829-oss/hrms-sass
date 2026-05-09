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
        Schema::table('attendance_logs', function (Blueprint $table) {
            // Drop unique index if exists
            $table->dropUnique(['tenant_id', 'employee_id', 'date']);
            // Add normal index back
            $table->index(['tenant_id', 'employee_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_logs', function (Blueprint $table) {
            $table->dropIndex(['tenant_id', 'employee_id', 'date']);
            $table->unique(['tenant_id', 'employee_id', 'date']);
        });
    }
};
