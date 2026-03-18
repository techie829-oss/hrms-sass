<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('timesheets')) {
            Schema::table('timesheets', function (Blueprint $table) {
                if (!Schema::hasColumn('timesheets', 'start_time')) {
                    $table->time('start_time')->after('date')->nullable();
                }
                if (!Schema::hasColumn('timesheets', 'end_time')) {
                    $table->time('end_time')->after('start_time')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        Schema::table('timesheets', function (Blueprint $table) {
            $table->dropColumn(['start_time', 'end_time']);
        });
    }
};
