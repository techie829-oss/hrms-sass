<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use App\SaaS\Billing\Plan;
use App\SaaS\Modules\ModuleManager;
use App\SaaS\Tenancy\TenantManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TenantController extends Controller
{
    public function __construct(
        protected TenantManager $tenantManager,
        protected ModuleManager $moduleManager
    ) {}

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
     * Display the specified tenant.
     */
    public function show(Tenant $tenant)
    {
        $tenant->load(['domains', 'subscription']);
        $plans = Plan::all();
        $availableModules = DB::table('modules')->get();
        $enabledModules = DB::table('tenant_modules')
            ->where('tenant_id', $tenant->id)
            ->where('enabled', true)
            ->pluck('module_id')
            ->toArray();
            
        // Fetch Admin users associated with this tenant
        $tenantUsers = User::where('tenant_id', $tenant->id)->get();
            
        // Fetch recent activity related to this tenant
        $activities = \Spatie\Activitylog\Models\Activity::where('tenant_id', $tenant->id)
            ->latest()
            ->take(15)
            ->get();

        return view('admin.tenants.show', compact(
            'tenant', 'plans', 'availableModules', 'enabledModules', 'tenantUsers', 'activities'
        ));
    }

    /**
     * Show the form for editing the specified tenant.
     */
    public function edit(Tenant $tenant)
    {
        $plans = Plan::all();
        return view('admin.tenants.edit', compact('tenant', 'plans'));
    }

    /**
     * Update the specified tenant.
     */
    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'contact_no' => ['required', 'string', 'max:20'],
        ]);

        $tenant->update($validated);

        return redirect()->route('admin.tenants.show', $tenant)
            ->with('success', 'Tenant details updated successfully.');
    }

    /**
     * Update the tenant's subscription plan.
     */
    public function updatePlan(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'plan_id' => 'required|string|exists:plans,slug',
        ]);

        $tenant->update(['plan_id' => $validated['plan_id']]);

        // Could dispatch events here to handle module limits/billing syncs

        return back()->with('success', 'Tenant subscription plan updated.');
    }

    /**
     * Toggle a specific module for the tenant.
     */
    public function toggleModule(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'module_slug' => 'required|string',
            'action' => 'required|in:enable,disable',
        ]);

        $plan = Plan::where('slug', $tenant->plan_id)->first();
        $currentEnabledCount = DB::table('tenant_modules')
            ->where('tenant_id', $tenant->id)
            ->where('enabled', true)
            ->count();

        if ($validated['action'] === 'enable') {
            if ($plan && $currentEnabledCount >= $plan->max_modules && $plan->max_modules !== -1) {
                return back()->with('error', "Cannot enable module. Plan limit of {$plan->max_modules} reached.");
            }
            $this->moduleManager->enableModule($validated['module_slug'], $tenant);
            $msg = 'enabled';
        } else {
            $this->moduleManager->disableModule($validated['module_slug'], $tenant);
            $msg = 'disabled';
        }

        return back()->with('success', "Module {$validated['module_slug']} {$msg} successfully.");
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
