<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DemoLead;
use Illuminate\Support\Facades\DB;

class DemoLeadController extends Controller
{
    /**
     * Display both demo leads (landing page) and tenant CRM leads (Operations module).
     */
    public function index()
    {
        // 1. Demo leads from landing page form (public.demo_leads)
        $demoLeads = DemoLead::latest()->paginate(20, ['*'], 'demo_page');

        // 2. CRM leads from tenant shared schema (shared.leads)
        $crmLeads = DB::table('shared.leads')
            ->orderByDesc('created_at')
            ->paginate(20, ['*'], 'crm_page');

        return view('admin.leads.index', compact('demoLeads', 'crmLeads'));
    }
}
