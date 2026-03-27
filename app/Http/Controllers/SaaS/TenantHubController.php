<?php

namespace App\Http\Controllers\SaaS;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantHubController extends Controller
{
    /**
     * Show the tenant selection hub for logged-in users.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Find tenants associated with this user
        // Currently, we have a simple 1-to-1 tenant_id on User.
        // In the future, this could be a pivot table for 1-to-many.
        $tenants = [];
        if ($user->tenant_id) {
            $tenants = Tenant::where('id', $user->tenant_id)->get();
        }

        return view('saas.hub', compact('tenants'));
    }
}
