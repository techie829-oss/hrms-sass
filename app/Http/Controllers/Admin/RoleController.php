<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $query = Role::withCount('users', 'permissions');

        if ($request->filled('context')) {
            if ($request->context === 'global') {
                $query->whereNull('tenant_id');
            } else {
                $query->where('tenant_id', $request->context);
            }
        }

        $roles = $query->get();
        
        // Fetch unique contexts (tenant IDs) for the filter dropdown
        $contexts = Role::whereNotNull('tenant_id')
            ->distinct()
            ->pluck('tenant_id');

        return view('admin.roles.index', compact('roles', 'contexts'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|alpha_dash|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create(['name' => $validated['name'], 'guard_name' => 'web']);
        
        if (!empty($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|alpha_dash|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->update(['name' => $validated['name']]);
        
        if (isset($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        } else {
            $role->syncPermissions([]);
        }

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        // Prevent deleting core system roles
        $coreRoles = [
            \App\Core\Constants\RoleConstants::SADMIN,
            \App\Core\Constants\RoleConstants::TADMIN,
            \App\Core\Constants\RoleConstants::TSTAFF,
        ];

        if (in_array(strtolower($role->name), $coreRoles)) {
            return back()->with('error', 'Core system roles cannot be deleted.');
        }

        $role->delete();
        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
    }
}
