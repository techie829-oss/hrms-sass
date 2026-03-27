<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->string('id')->primary();

            $table->string('name')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->string('domain')->unique()->nullable();
            $table->string('schema')->nullable(); // PostgreSQL schema name
            $table->enum('mode', ['shared', 'dedicated'])->default('shared');
            $table->string('plan_id')->nullable();
            $table->string('status')->default('active');
            $table->string('email')->nullable();
            $table->string('contact_no')->nullable();
            $table->timestamps();
            $table->json('data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
}
