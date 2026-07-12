<?php

namespace App\Modules\Leave\Controllers;

use App\Core\BaseController;
use App\Modules\Leave\Models\CompOffRequest;
use App\Modules\HR\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Core\Constants\PermissionConstants;
use App\Modules\Leave\Services\CompOffService;
use App\Modules\Leave\Requests\StoreCompOffRequest;
use App\Modules\Leave\Requests\BulkGrantCompOffRequest;
use App\Modules\Leave\Requests\UpdateCompOffStatusRequest;
use App\Modules\Leave\Requests\SettleBulkCompOffRequest;
use App\Modules\Leave\DTOs\CompOffData;

class CompOffController extends BaseController
{
    public function __construct(
        protected CompOffService $compOffService
    ) {}

    public function index()
    {
        $this->authorize('viewAny', CompOffRequest::class);
        $user = Auth::user();
        
        $filters = [];
        
        if (!$user->can(PermissionConstants::MANAGE_COMP_OFF)) {
            $filters['employee_id'] = $user->employee?->id;
        }

        $requests = $this->compOffService->getPaginatedList($filters, 15);
        return view('leave::comp_off.index', compact('requests'));
    }

    public function store(StoreCompOffRequest $request)
    {
        $this->authorize('create', CompOffRequest::class);
        $validated = $request->validated();

        $dto = CompOffData::fromArray($validated, saas_tenant('id'), Auth::user()->employee?->id);
        $this->compOffService->createRequest($dto);

        return back()->with('success', 'Comp-off request submitted.');
    }

    /**
     * Bulk grant comp-off to all employees present on a specific date.
     */
    public function bulkGrant(BulkGrantCompOffRequest $request)
    {
        $this->authorize('manage', CompOffRequest::class);
        $validated = $request->validated();

        $count = $this->compOffService->bulkGrant(
            $validated['date'],
            $validated['reason'],
            Auth::id(),
            saas_tenant('id')
        );

        return back()->with('success', "Comp-off granted to {$count} employees.");
    }

    public function updateStatus(UpdateCompOffStatusRequest $request, CompOffRequest $compOffRequest)
    {
        $this->authorize('manage', CompOffRequest::class);
        $validated = $request->validated();

        $this->compOffService->updateStatus($compOffRequest, $validated['status'], Auth::id());

        return back()->with('success', 'Request ' . $validated['status']);
    }

    /**
     * One-click settlement of comp-offs.
     * Takes earned date and target usage date.
     */
    public function settleBulk(SettleBulkCompOffRequest $request)
    {
        $this->authorize('manage', CompOffRequest::class);
        $validated = $request->validated();

        $result = $this->compOffService->settleBulk(
            $validated['reference_date'],
            $validated['target_date'],
            Auth::id(),
            saas_tenant('id')
        );

        return back()->with('success', "Settled {$result['settled']} claims. Skipped {$result['skipped']} employees (Present on target date).");
    }
}
