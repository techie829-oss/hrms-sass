<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $teamKey = $columnNames['team_foreign_key'] ?? 'tenant_id';

        // 1. Add tenant_id to Roles and Permissions
        Schema::table($tableNames['roles'], function (Blueprint $table) use ($teamKey) {
            if (!Schema::hasColumn($table->getTable(), $teamKey)) {
                $table->string($teamKey)->nullable()->after('guard_name')->index();
            }
        });

        Schema::table($tableNames['permissions'], function (Blueprint $table) use ($teamKey) {
            if (!Schema::hasColumn($table->getTable(), $teamKey)) {
                $table->string($teamKey)->nullable()->after('guard_name')->index();
            }
        });

        // 2. Add tenant_id to Pivot tables
        Schema::table($tableNames['model_has_roles'], function (Blueprint $table) use ($teamKey) {
            if (!Schema::hasColumn($table->getTable(), $teamKey)) {
                $table->string($teamKey)->nullable()->index();
            }
        });

        Schema::table($tableNames['model_has_permissions'], function (Blueprint $table) use ($teamKey) {
            if (!Schema::hasColumn($table->getTable(), $teamKey)) {
                $table->string($teamKey)->nullable()->index();
            }
        });

        Schema::table($tableNames['role_has_permissions'], function (Blueprint $table) use ($teamKey) {
            if (!Schema::hasColumn($table->getTable(), $teamKey)) {
                $table->string($teamKey)->nullable()->index();
            }
        });
    }

    public function down(): void
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $teamKey = $columnNames['team_foreign_key'] ?? 'tenant_id';

        Schema::table($tableNames['roles'], function (Blueprint $table) use ($teamKey) {
            $table->dropColumn($teamKey);
        });

        Schema::table($tableNames['permissions'], function (Blueprint $table) use ($teamKey) {
            $table->dropColumn($teamKey);
        });

        Schema::table($tableNames['model_has_roles'], function (Blueprint $table) use ($teamKey) {
            $table->dropColumn($teamKey);
        });

        Schema::table($tableNames['model_has_permissions'], function (Blueprint $table) use ($teamKey) {
            $table->dropColumn($teamKey);
        });

        Schema::table($tableNames['role_has_permissions'], function (Blueprint $table) use ($teamKey) {
            $table->dropColumn($teamKey);
        });
    }
};
