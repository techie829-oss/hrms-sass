<?php

namespace App\Core\Constants;

/**
 * Reserved System Role Names for the HRMS SaaS Platform.
 * 
 * IMPORTANT:
 * - 'sadmin' is the primary/highest role (equivalent to Super Admin).
 * - All roles reside in the 'public.roles' table.
 */
class RoleConstants
{
    // ── SaaS Central Reserved Roles (tenant_id = NULL) ──────────────────
    public const SADMIN   = 'sadmin';   // Primary/Super Admin
    public const SMANAGER = 'smanager'; // SaaS Manager
    public const SSTAFF   = 'sstaff';    // SaaS Staff (Support)

    // ── Tenant Reserved Roles (tenant_id = REQUIRED) ────────────────────
    public const TADMIN   = 'tadmin';   // Tenant Owner
    public const TMANAGER = 'tmanager'; // Tenant Manager
    public const TSTAFF   = 'tstaff';   // Tenant Employee

    /**
     * Get all platform-reserved role names.
     */
    public static function getReservedRoles(): array
    {
        return array_merge(
            self::getCentralRoles(),
            self::getTenantRoles()
        );
    }

    public static function getCentralRoles(): array
    {
        return [self::SADMIN, self::SMANAGER, self::SSTAFF];
    }

    public static function getTenantRoles(): array
    {
        return [self::TADMIN, self::TMANAGER, self::TSTAFF];
    }
}
