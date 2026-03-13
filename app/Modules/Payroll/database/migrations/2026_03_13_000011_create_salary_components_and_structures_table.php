<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salary_components', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id')->index();
            $table->string('name'); // Basic, HRA, DA, Conveyance, Medical, PF, ESI, Tax, etc.
            $table->string('code', 20);
            $table->enum('type', ['earning', 'deduction']); // earning or deduction
            $table->enum('calculation_type', ['fixed', 'percentage'])->default('fixed');
            $table->decimal('default_amount', 12, 2)->default(0);
            $table->decimal('percentage_of', 12, 2)->nullable(); // e.g., HRA = 40% of basic
            $table->string('percentage_base')->nullable(); // which component's percentage
            $table->boolean('is_taxable')->default(true);
            $table->boolean('is_mandatory')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('display_order')->default(0);
            $table->timestamps();

            $table->unique(['tenant_id', 'code']);
        });

        Schema::create('salary_structures', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id')->index();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->decimal('ctc', 12, 2)->default(0); // cost to company
            $table->decimal('gross_salary', 12, 2)->default(0);
            $table->decimal('net_salary', 12, 2)->default(0);
            $table->json('earnings'); // [{component_id, amount}]
            $table->json('deductions'); // [{component_id, amount}]
            $table->date('effective_from');
            $table->date('effective_to')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('effective_from');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_structures');
        Schema::dropIfExists('salary_components');
    }
};
