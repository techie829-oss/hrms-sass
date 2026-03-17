<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            if (!Schema::hasColumn('job_postings', 'share_key')) {
                $table->string('share_key', 32)->nullable()->unique()->after('status');
            }
        });

        // Backfill any existing rows that don't have a share_key
        \Illuminate\Support\Facades\DB::table('job_postings')
            ->whereNull('share_key')
            ->orWhere('share_key', '')
            ->get()
            ->each(function ($posting) {
                \Illuminate\Support\Facades\DB::table('job_postings')
                    ->where('id', $posting->id)
                    ->update(['share_key' => Str::random(32)]);
            });
    }

    public function down(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->dropColumn('share_key');
        });
    }
};
