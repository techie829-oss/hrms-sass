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
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id')->index();
            $table->unsignedBigInteger('job_application_id')->index();
            $table->unsignedBigInteger('interviewer_id')->nullable()->index();
            $table->datetime('interview_date');
            $table->string('location')->nullable();
            $table->string('type')->default('technical'); // technical, hr, behavioral
            $table->string('status')->default('scheduled'); // scheduled, completed, cancelled
            $table->text('feedback')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};
