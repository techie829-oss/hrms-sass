<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $permissionModel = config('permission.models.permission');
        $permissions = $permissionModel::all();

        foreach ($permissions as $permission) {
            // Replace both spaces and underscores with hyphens
            $newName = str_replace([' ', '_'], '-', $permission->name);
            $newName = strtolower($newName);

            if ($newName !== $permission->name) {
                // Check if a permission with the new name already exists
                $existing = $permissionModel::where('name', $newName)->first();

                if ($existing) {
                    // Merge logic: If a duplicate exists, we delete the old one.
                    // This is safe because permissions will be re-assigned via seeders if needed.
                    $permission->delete();
                } else {
                    $permission->update(['name' => $newName]);
                }
            }
        }

        // Clear Spatie cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
