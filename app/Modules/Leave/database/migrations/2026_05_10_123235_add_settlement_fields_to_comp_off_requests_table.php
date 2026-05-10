<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('comp_off_requests', function (Blueprint $table) {
            $table->foreignId('leave_request_id')->nullable()->constrained('leave_requests')->nullOnDelete();
            $table->boolean('is_used')->default(false);
            $table->date('used_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('comp_off_requests', function (Blueprint $table) {
            $table->dropForeign(['leave_request_id']);
            $table->dropColumn(['leave_request_id', 'is_used', 'used_at']);
        });
    }
};
