<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Operations\Models\Lead;
use Illuminate\Http\Request;

class DemoLeadController extends Controller
{
    /**
     * Display a listing of the demo leads.
     */
    public function index()
    {
        // Fetch leads without tenant scope so superadmin can see all of them
        $leads = Lead::withoutGlobalScopes()->latest()->paginate(15);
        
        return view('admin.leads.index', compact('leads'));
    }
}
