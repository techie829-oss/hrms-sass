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
        Schema::create('kpis', function (Blueprint $row) {
            $row->id();
            $row->string('tenant_id')->index();
            $row->string('name');
            $row->text('description')->nullable();
            $row->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');
            $row->decimal('target_value', 10, 2);
            $row->string('unit')->default('number');
            $row->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kpis');
    }
};
