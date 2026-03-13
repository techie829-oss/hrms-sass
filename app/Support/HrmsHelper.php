<?php

namespace App\Support;

use App\SaaS\Modules\ModuleManager;

class HrmsHelper
{
    /**
     * Generate employee ID.
     */
    public static function generateEmployeeId(): string
    {
        $prefix = config('hrms.employee.id_prefix', 'EMP');
        $length = config('hrms.employee.id_length', 6);
        $number = str_pad((string) random_int(1, (int) str_repeat('9', $length)), $length, '0', STR_PAD_LEFT);

        return "{$prefix}-{$number}";
    }

    /**
     * Format currency.
     */
    public static function formatCurrency(float $amount): string
    {
        $symbol = config('billing.currency_symbol', '₹');

        return $symbol.number_format($amount, 2);
    }

    /**
     * Get module display name.
     */
    public static function moduleDisplayName(string $slug): string
    {
        return ucfirst(str_replace('_', ' ', $slug));
    }

    /**
     * Check if current user has module access.
     */
    public static function hasModuleAccess(string $module): bool
    {
        $tenant = tenant();
        if (! $tenant) {
            return false;
        }

        $manager = app(ModuleManager::class);

        return $manager->tenantHasAccess($module, $tenant->plan ?? 'free');
    }
}
