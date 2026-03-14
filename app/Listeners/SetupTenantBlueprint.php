<?php

namespace App\Listeners;

use App\Events\TenantProvisioned;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SetupTenantBlueprint
{
    /**
     * Handle the event.
     */
    public function handle(TenantProvisioned $event): void
    {
        $tenant = $event->tenant;

        // 1. Create/Update the primary admin user for this tenant
        $adminEmail = $tenant->email;
        
        // Use updateOrCreate to handle existing users
        // Note: In a real system, you might want to prevent using an existing email for a new tenant admin
        $user = User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'tenant_id' => $tenant->id,
                'name' => $tenant->name . ' Admin',
                'password' => Hash::make('password'), // Ideally, trigger a password reset email
                'email_verified_at' => now(),
            ]
        );

        // 2. Assign the HR Manager role
        // We use syncRoles to ensure they only have the necessary role for this context
        // and avoid duplicates.
        if (!$user->hasRole('super_admin')) {
            $user->syncRoles(['hr_manager']);
        }
    }
}
