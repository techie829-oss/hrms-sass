<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use App\Core\Constants\RoleConstants;
use App\Core\Constants\PermissionConstants;

class RoleController extends Controller
{
    public function index()
    {
        $tenantId = saas_tenant('id');

        // Fetch roles specifically scoped to this tenant (including tadmin/tmanager/tstaff & custom roles)
        $roles = Role::where('tenant_id', $tenantId)
            ->withCount(['users', 'permissions'])
            ->get();

        return view('tenant.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all()->groupBy(function ($permission) {
            $parts = explode('.', $permission->name);
            return count($parts) > 1 ? $parts[1] : 'general';
        });

        return view('tenant.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $tenantId = saas_tenant('id');

        $validated = $request->validate([
            'name' => 'required|string|max:255|alpha_dash',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        // Ensure role name is unique within this tenant
        $exists = Role::where('tenant_id', $tenantId)
            ->where('name', $validated['name'])
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'A role with this name already exists for your company.');
        }

        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
            'tenant_id' => $tenantId,
        ]);

        if (!empty($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        return redirect()->route('tenant.roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        $tenantId = saas_tenant('id');

        // Protect central system roles from being edited by tenant
        if (in_array($role->name, RoleConstants::getReservedRoles()) && $role->tenant_id === null) {
            return back()->with('error', 'System default roles cannot be edited.');
        }

        if ($role->tenant_id !== null && $role->tenant_id !== $tenantId) {
            abort(403, 'Unauthorized access to role.');
        }

        $permissions = Permission::all()->groupBy(function ($permission) {
            $parts = explode('.', $permission->name);
            return count($parts) > 1 ? $parts[1] : 'general';
        });

        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('tenant.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $tenantId = saas_tenant('id');

        if (in_array($role->name, RoleConstants::getReservedRoles()) && $role->tenant_id === null) {
            return back()->with('error', 'System default roles cannot be modified.');
        }

        if ($role->tenant_id !== null && $role->tenant_id !== $tenantId) {
            abort(403, 'Unauthorized access to role.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|alpha_dash',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->update(['name' => $validated['name']]);

        if (isset($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        } else {
            $role->syncPermissions([]);
        }

        return redirect()->route('tenant.roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $tenantId = saas_tenant('id');

        if (in_array($role->name, RoleConstants::getReservedRoles())) {
            return back()->with('error', 'Core system default roles cannot be deleted.');
        }

        if ($role->tenant_id !== $tenantId) {
            return back()->with('error', 'Unauthorized action.');
        }

        $role->delete();
        return redirect()->route('tenant.roles.index')->with('success', 'Role deleted successfully.');
    }
}
