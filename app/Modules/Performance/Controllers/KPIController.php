<?php

namespace App\Modules\Performance\Controllers;

use App\Core\BaseController;
use App\Modules\Performance\Models\KPI;
use App\Modules\HR\Models\Department;
use Illuminate\Http\Request;

class KPIController extends BaseController
{
    public function index()
    {
        $kpis = KPI::with('department')->paginate(15);
        $departments = Department::all();
        return view('performance::kpis.index', compact('kpis', 'departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'target_value' => ['required', 'numeric'],
            'unit' => ['required', 'string', 'max:50'],
        ]);

        KPI::create($validated);

        return redirect()->route('performance.kpis.index')
            ->with('success', 'KPI created successfully.');
    }
}
