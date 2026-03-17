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
        Schema::table('plans', function (Blueprint $table) {
            $table->string('razorpay_plan_id')->nullable()->after('slug');
        });

        Schema::table('tenant_subscriptions', function (Blueprint $table) {
            $table->string('razorpay_id')->nullable()->after('plan_id');
            $table->string('razorpay_payment_id')->nullable()->after('razorpay_id');
            $table->string('razorpay_signature')->nullable()->after('razorpay_payment_id');
            $table->json('meta')->nullable()->after('cancelled_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('razorpay_plan_id');
        });

        Schema::table('tenant_subscriptions', function (Blueprint $table) {
            $table->dropColumn(['razorpay_id', 'razorpay_payment_id', 'razorpay_signature', 'meta']);
        });
    }
};
