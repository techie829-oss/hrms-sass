<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

use App\Core\Constants\RoleConstants;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();
        
        // Default fallback if no specific dashboard is found
        $dashboardRoute = 'tenant.dashboard'; 

        if ($user->hasRole(RoleConstants::SADMIN)) {
            $dashboardRoute = 'super-admin.dashboard';
        } elseif (function_exists('saas_tenant') && saas_tenant()) {
            $dashboardRoute = 'tenant.dashboard';
        }

        return redirect()->intended(route($dashboardRoute, absolute: true));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
