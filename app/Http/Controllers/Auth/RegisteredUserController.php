<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\SaaS\Tenancy\TenantManager;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function __construct(protected TenantManager $tenantManager) {}

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'subdomain' => ['required', 'string', 'max:255', 'unique:tenants,slug', 'alpha_dash'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 1. Provision Tenant
        $baseUrl = parse_url(config('app.url'), PHP_URL_HOST) ?: config('app.url');
        $tenantDomain = $request->subdomain.'.'.$baseUrl;

        $tenant = $this->tenantManager->provision([
            'id' => $request->subdomain,
            'name' => $request->company_name,
            'email' => $request->email,
            'domain' => $tenantDomain,
            'mode' => 'shared', // Default to shared for self-service
            'plan_id' => 'trial',
        ]);

        // 2. Create User linked to tenant
        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign HR Manager role to the first user
        $user->assignRole('hr_manager');

        event(new Registered($user));

        Auth::login($user);

        // 3. Redirect to the tenant subdomain
        return redirect()->away('http://'.$tenantDomain.'/dashboard');
    }
}
