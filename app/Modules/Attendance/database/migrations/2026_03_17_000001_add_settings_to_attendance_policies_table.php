<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendance_policies', function (Blueprint $table) {
            $table->boolean('is_kiosk_enabled')->default(true)->after('is_active');
            $table->boolean('kiosk_require_photo')->default(true)->after('is_kiosk_enabled');
            $table->boolean('kiosk_require_location')->default(true)->after('kiosk_require_photo');
            $table->boolean('is_mobile_enabled')->default(true)->after('kiosk_require_location');
            $table->boolean('is_manual_enabled')->default(true)->after('is_mobile_enabled');
        });
    }

    public function down(): void
    {
        Schema::table('attendance_policies', function (Blueprint $table) {
            $table->dropColumn([
                'is_kiosk_enabled',
                'kiosk_require_photo',
                'kiosk_require_location',
                'is_mobile_enabled',
                'is_manual_enabled'
            ]);
        });
    }
};
