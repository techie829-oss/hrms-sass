<?php

namespace App\Modules\Leave\Controllers;

use App\Core\BaseController;
use App\Modules\Leave\Models\LeaveRequest;
use App\Modules\Leave\Models\LeaveType;
use App\Modules\Leave\Models\LeaveBalance;
use App\Modules\Leave\Models\CompOffRequest;
use App\Modules\HR\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Core\Constants\PermissionConstants;
use App\Modules\Leave\Services\LeaveCalculationService;
use App\Modules\Leave\Services\LeaveBalanceService;
use App\Modules\Leave\Services\LeaveRequestService;
use App\Modules\Leave\Services\LeaveTypeService;
use App\Modules\HR\Interfaces\EmployeeRepositoryInterface;
use App\Modules\Leave\Requests\StoreLeaveRequest;
use App\Modules\Leave\Requests\BulkStoreLeaveRequest;
use App\Modules\Leave\Requests\UpdateLeaveStatusRequest;
use App\Modules\Leave\DTOs\LeaveRequestData;
use App\Modules\Leave\DTOs\LeaveRequestBulkData;

class LeaveRequestController extends BaseController
{
    protected $calculationService;
    protected $balanceService;
    protected $leaveRequestService;
    protected $leaveTypeService;
    protected $employeeRepository;

    public function __construct(
        LeaveCalculationService $calculationService,
        LeaveBalanceService $balanceService,
        LeaveRequestService $leaveRequestService,
        LeaveTypeService $leaveTypeService,
        EmployeeRepositoryInterface $employeeRepository
    ) {
        $this->calculationService = $calculationService;
        $this->balanceService = $balanceService;
        $this->leaveRequestService = $leaveRequestService;
        $this->leaveTypeService = $leaveTypeService;
        $this->employeeRepository = $employeeRepository;
    }

    /**
     * Display a listing of leave requests.
     */
    public function index()
    {
        $this->authorize('viewAny', LeaveRequest::class);
        
        $user = Auth::user();
        $query = LeaveRequest::with(['employee', 'leaveType']);

        // Staff can only see their own requests unless they have broader view permissions
        if (!$user->can(PermissionConstants::MANAGE_LEAVE)) {
            $employee = $user->employee;
            $query->where('employee_id', $employee->id ?? 0);
        }

        $requests = $query->latest()->paginate(15);
        $employees = $this->employeeRepository->getActiveEmployees();
        $leaveTypes = $this->leaveTypeService->getActiveLeaveTypes();

        return view('leave::index', compact('requests', 'employees', 'leaveTypes'));
    }

    /**
     * Show form to create a new leave request.
     */
    public function create()
    {
        $this->authorize('create', LeaveRequest::class);
        
        $leaveTypes = $this->leaveTypeService->getActiveLeaveTypes();
        $user = Auth::user();
        
        $isAdmin = $user->can(PermissionConstants::MANAGE_LEAVE);
        $employees = $isAdmin 
            ? $this->employeeRepository->getActiveEmployees()
            : $this->employeeRepository->all(['user_id' => $user->id]);

        $employee = $user->employee;
        $balances = [];
        if ($employee) {
            $balances = $this->balanceService->getBalancesForEmployee($employee->id, now()->year);
        }

        return view('leave::create', compact('leaveTypes', 'employees', 'isAdmin', 'balances'));
    }

    /**
     * Store a newly created leave request.
     */
    public function store(StoreLeaveRequest $request)
    {
        $this->authorize('create', LeaveRequest::class);

        $validated = $request->validated();

        // Security check: Staff cannot apply for others
        if (!$request->user()->can(PermissionConstants::MANAGE_LEAVE)) {
            $validated['employee_id'] = $request->user()->employee?->id;
        }

        try {
            $dto = LeaveRequestData::fromArray($validated);
            $this->leaveRequestService->createLeaveRequest($dto);

            return redirect()->route('leave.requests.index')
                ->with('success', 'Leave request submitted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Approve or reject a leave request.
     */
    public function updateStatus(UpdateLeaveStatusRequest $request, LeaveRequest $leaveRequest)
    {
        $this->authorize('approve', $leaveRequest);

        $validated = $request->validated();

        $this->leaveRequestService->updateStatus($leaveRequest, $validated, Auth::id());

        return redirect()->route('leave.requests.index')
            ->with('success', 'Leave request status updated.');
    }

    /**
     * Store multiple leave requests at once (Bulk Apply).
     */
    public function bulkStore(BulkStoreLeaveRequest $request)
    {
        $this->authorize('create', LeaveRequest::class);

        $validated = $request->validated();

        try {
            $dto = LeaveRequestBulkData::fromArray($validated);
            $result = $this->leaveRequestService->createBulkLeaveRequests($dto, auth()->id());
            
            $message = "Bulk leave applied for {$result['success_count']} employees.";
            if (count($result['errors']) > 0) {
                $message .= " Errors: " . implode(', ', $result['errors']);
            }

            return redirect()->route('leave.requests.index')->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display a single leave request.
     */
    public function show(LeaveRequest $leaveRequest)
    {
        $this->authorize('view', $leaveRequest);
        $leaveRequest->load(['employee', 'leaveType']);

        return view('leave::show', compact('leaveRequest'));
    }
}
