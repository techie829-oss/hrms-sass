<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\SaaS\Tenancy\TenantManager;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TenantController extends Controller
{
    public function __construct(protected TenantManager $tenantManager) {}

    /**
     * Display a listing of tenants.
     */
    public function index()
    {
        $tenants = Tenant::with('domains')->latest()->paginate(10);

        return view('admin.tenants.index', compact('tenants'));
    }

    /**
     * Show the form for creating a new tenant.
     */
    public function create()
    {
        return view('admin.tenants.create');
    }

    /**
     * Store a newly created tenant.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'contact_no' => ['required', 'string', 'max:20'],
            'subdomain' => ['required', 'string', 'alpha_dash', 'max:255', 'unique:tenants,slug'],
            'mode' => ['required', Rule::in(['shared', 'dedicated'])],
            'plan_id' => ['required', 'string'],
        ]);

        $host = parse_url(config('app.url'), PHP_URL_HOST) ?? config('app.url');
        $domain = $validated['subdomain'] . '.' . $host;

        $this->tenantManager->provision([
            'id' => $validated['subdomain'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'domain' => $domain,
            'mode' => $validated['mode'],
            'plan_id' => $validated['plan_id'],
            'contact_no' => $validated['contact_no'],
        ]);

        return redirect()->route('admin.tenants.index')
            ->with('success', "Tenant '{$validated['name']}' provisioned successfully.");
    }

    /**
     * Toggle tenant status.
     */
    public function toggleStatus(Tenant $tenant)
    {
        $newStatus = ($tenant->status === 'active') ? 'suspended' : 'active';
        $tenant->update(['status' => $newStatus]);

        return back()->with('success', "Tenant status updated to {$newStatus}.");
    }

    /**
     * Remove the specified tenant.
     */
    public function destroy(Tenant $tenant)
    {
        $tenant->delete();

        return redirect()->route('admin.tenants.index')
            ->with('success', 'Tenant removed from system.');
    }
}
