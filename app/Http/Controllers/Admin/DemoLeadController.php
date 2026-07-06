<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DemoLead;
use Illuminate\Http\Request;

class DemoLeadController extends Controller
{
    public function index()
    {
        $leads = DemoLead::latest()->paginate(15);
        return view('admin.leads.index', compact('leads'));
    }
}
