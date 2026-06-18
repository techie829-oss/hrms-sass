<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DemoLead;
use Illuminate\Support\Facades\Cookie;

class DemoController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
        ]);

        DemoLead::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'mobile' => $validated['mobile'],
            'country' => $validated['country'],
            'company_name' => $validated['company_name'],
            'ip_address' => $request->ip(),
        ]);

        // 3 days = 3 * 24 * 60 = 4320 minutes
        Cookie::queue('demo_access_granted', true, 4320);

        return redirect('https://demo.hr.solidrix.com/');
    }
}
