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
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->string('domain')->unique()->nullable();
            $table->string('schema')->nullable(); // PostgreSQL schema name
            $table->enum('mode', ['shared', 'dedicated'])->default('shared');
            $table->string('plan_id')->nullable();
            $table->string('status')->default('active');
            $table->string('email')->nullable(); // Moved from previous attempt
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['name', 'slug', 'domain', 'schema', 'mode', 'plan_id', 'status', 'email']);
        });
    }
};
