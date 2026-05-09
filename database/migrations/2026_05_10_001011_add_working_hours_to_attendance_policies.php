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
        Schema::table('attendance_policies', function (Blueprint $col) {
            $col->boolean('auto_checkout')->default(false)->after('allow_multi_clocking');
            $col->time('auto_checkout_time')->nullable()->after('auto_checkout');
            $col->time('default_start_time')->nullable()->after('auto_checkout_time');
            $col->time('default_end_time')->nullable()->after('default_start_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_policies', function (Blueprint $col) {
            $col->dropColumn(['auto_checkout', 'auto_checkout_time', 'default_start_time', 'default_end_time']);
        });
    }
};
