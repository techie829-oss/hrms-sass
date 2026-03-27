<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 0. Schema Cleanup (Permanent Fix for Multi-Schema Persistence)
        // If we are seeding the central app, we should also wipe other schemas to prevent stale data
        if (!tenant()) {
            $schemas = \DB::select("SELECT schema_name FROM information_schema.schemata WHERE schema_name LIKE 'tenant_%' OR schema_name = 'shared'");
            foreach ($schemas as $schema) {
                \DB::statement("DROP SCHEMA IF EXISTS \"{$schema->schema_name}\" CASCADE");
                \Log::info("Seeder Cleanup: Dropped schema {$schema->schema_name}");
            }
        }

        // 1. Central App Seeding (Public Schema)
        if (!tenant()) {
            $this->call([
                PlanSeeder::class,
                InternalUserSeeder::class,
            ]);
        }

        // 2. Tenant App Seeding (Shared or Dedicated Schema)
        if (tenant()) {
            // Future tenant-specific seeders go here
        }
    }
}
