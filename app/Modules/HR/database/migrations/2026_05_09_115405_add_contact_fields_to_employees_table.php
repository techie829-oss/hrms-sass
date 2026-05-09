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
        Schema::table('employees', function (Blueprint $table) {
            $table->string('country_code', 10)->default('+91')->after('email')->comment('Country code for primary phone');
            $table->string('alt_country_code', 10)->nullable()->after('phone')->comment('Country code for alt phone');
            $table->string('personal_email')->nullable()->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['country_code', 'alt_country_code', 'personal_email']);
        });
    }
};
