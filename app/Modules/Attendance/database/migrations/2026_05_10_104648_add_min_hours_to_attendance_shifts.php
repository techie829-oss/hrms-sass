<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendance_shifts', function (Blueprint $table) {
            $table->unsignedSmallInteger('min_hours_full_day')->nullable()->after('half_day_hours');
        });
    }

    public function down(): void
    {
        Schema::table('attendance_shifts', function (Blueprint $table) {
            $table->dropColumn('min_hours_full_day');
        });
    }
};
