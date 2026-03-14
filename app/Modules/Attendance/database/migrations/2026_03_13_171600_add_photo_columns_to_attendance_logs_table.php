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
            $table->string('check_in_photo')->nullable()->after('check_in_lng');
            $table->string('check_out_photo')->nullable()->after('check_in_photo');
            $table->decimal('check_out_lat', 10, 7)->nullable()->after('check_out_photo');
            $table->decimal('check_out_lng', 10, 7)->nullable()->after('check_out_lat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_logs', function (Blueprint $table) {
            $table->dropColumn(['check_in_photo', 'check_out_photo', 'check_out_lat', 'check_out_lng']);
        });
    }
};
