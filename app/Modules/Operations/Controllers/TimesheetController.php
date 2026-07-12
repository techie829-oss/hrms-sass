<?php

namespace App\Modules\Operations\Controllers;

use App\Core\BaseController;
use App\Modules\Operations\Models\Timesheet;
use App\Modules\Operations\Models\Project;
use App\Modules\Operations\Models\Task;
use Illuminate\Http\Request;
use App\Modules\Operations\Requests\StoreTimesheetRequest;
use App\Modules\Operations\DTOs\TimesheetData;
use App\Modules\Operations\Services\TimesheetService;

class TimesheetController extends BaseController
{
    public function __construct(
        protected TimesheetService $timesheetService
    ) {
        // Authorization handled in methods if needed
    }

    public function index()
    {
        $this->authorize('viewAny', Timesheet::class);
        $timesheets = Timesheet::where('tenant_id', saas_tenant('id'))
            ->with(['employee', 'project', 'task'])
            ->latest()
            ->paginate(15);

        $projects = Project::where('tenant_id', saas_tenant('id'))->get();

        return view('operations::timesheets.index', compact('timesheets', 'projects'));
    }

    public function store(StoreTimesheetRequest $request)
    {
        $this->authorize('create', Timesheet::class);
        
        $employeeId = auth()->user()->employee->id ?? null;

        if (!$employeeId) {
            return back()->with('error', 'Only employees can log timesheets.');
        }

        $dto = TimesheetData::fromArray($request->validated(), saas_tenant('id'), $employeeId);
        $this->timesheetService->createTimesheet($dto);

        return back()->with('success', 'Daily sprint log submitted successfully.');
    }
}
