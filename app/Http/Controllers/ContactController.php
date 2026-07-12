<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'email'          => 'required|email|max:255',
            'phone'          => 'nullable|string|max:20',
            'company_name'   => 'nullable|string|max:255',
            'employee_count' => 'nullable|string|max:50',
            'inquiry_type'   => 'nullable|string|max:100',
            'message'        => 'required|string|max:3000',
        ]);

        DB::table('contact_messages')->insert([
            'first_name'     => $validated['first_name'],
            'last_name'      => $validated['last_name'],
            'email'          => $validated['email'],
            'phone'          => $validated['phone'] ?? null,
            'company_name'   => $validated['company_name'] ?? null,
            'employee_count' => $validated['employee_count'] ?? null,
            'inquiry_type'   => $validated['inquiry_type'] ?? null,
            'message'        => $validated['message'],
            'ip_address'     => $request->ip(),
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        return redirect()->route('central.contact')
            ->with('success', 'Thank you! Your message has been received. We will get back to you within 1 business day.');
    }
}
