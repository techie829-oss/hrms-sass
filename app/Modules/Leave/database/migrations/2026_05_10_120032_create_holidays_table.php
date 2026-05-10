<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id')->index();
            $table->string('name');
            $table->date('date');
            $table->boolean('is_optional')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('holidays');
    }
};
