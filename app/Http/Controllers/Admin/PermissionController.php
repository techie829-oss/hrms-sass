<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of permissions.
     */
    public function index(Request $request)
    {
        $query = Permission::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->get('search') . '%');
        }

        $permissions = $query->latest()->paginate(15);

        foreach ($permissions as $permission) {
            $permission->feature_description = $permission->description 
                ?: 'Governs custom action: ' . ucwords(str_replace('-', ' ', $permission->name));
        }

        return view('admin.permissions.index', compact('permissions'));
    }

    /**
     * Store a newly created permission.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            'description' => 'nullable|string|max:500',
        ]);

        Permission::create([
            'name' => strtolower(preg_replace('/[\s\-]+/', '_', trim($validated['name']))),
            'description' => $validated['description'],
            'guard_name' => 'web',
        ]);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission created successfully.');
    }

    /**
     * Show the form for editing the specified permission.
     */
    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    /**
     * Update the specified permission in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            'description' => 'nullable|string|max:500',
        ]);

        $permission->update([
            'name' => strtolower(preg_replace('/[\s\-]+/', '_', trim($validated['name']))),
            'description' => $validated['description'],
        ]);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission updated successfully.');
    }

    /**
     * Remove the specified permission from storage.
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();

        return redirect()->route('admin.permissions.index')->with('success', 'Permission deleted successfully.');
    }
}
