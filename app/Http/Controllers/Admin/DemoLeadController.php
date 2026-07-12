<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DemoLead;
use Illuminate\Http\Request;

class DemoLeadController extends Controller
{
    /**
     * Display a listing of demo leads from the landing page.
     */
    public function index()
    {
        $leads = DemoLead::latest()->paginate(20);

        return view('admin.leads.index', compact('leads'));
    }
}
