<?php

namespace App\Modules\Leave\Controllers;

use App\Core\BaseController;
use App\Modules\Leave\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HolidayController extends BaseController
{
    public function index()
    {
        $this->authorize('viewAny', Holiday::class);
        $holidays = Holiday::orderBy('date')->get();
        return view('leave::holidays.index', compact('holidays'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Holiday::class);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'is_optional' => 'boolean',
            'description' => 'nullable|string',
        ]);

        Holiday::create(array_merge($validated, ['tenant_id' => saas_tenant('id')]));

        return back()->with('success', 'Holiday added successfully.');
    }

    public function destroy(Holiday $holiday)
    {
        $this->authorize('delete', $holiday);
        $holiday->delete();
        return back()->with('success', 'Holiday deleted successfully.');
    }
}
