<?php

namespace App\Modules\Recruitment\Controllers;

use App\Core\BaseController;
use App\Modules\Recruitment\Models\JobApplication;
use App\Modules\Recruitment\Models\JobPosting;
use App\Modules\Recruitment\Models\Interview;
use App\Modules\HR\Models\Employee;
use Illuminate\Http\Request;
use App\Modules\Recruitment\Services\JobApplicationService;
use App\Modules\Recruitment\Requests\UpdateJobApplicationStatusRequest;
use App\Modules\Recruitment\Requests\ScheduleInterviewRequest;
use App\Modules\Recruitment\Requests\UpdateInterviewRequest;
use App\Modules\Recruitment\DTOs\UpdateJobApplicationStatusData;
use App\Modules\Recruitment\DTOs\ScheduleInterviewData;
use App\Modules\Recruitment\DTOs\UpdateInterviewData;
use App\Modules\Recruitment\Services\JobPostingService;
use App\Modules\HR\Services\EmployeeService;

class JobApplicationController extends BaseController
{
    public function __construct(
        protected JobApplicationService $jobApplicationService,
        protected JobPostingService $jobPostingService,
        protected EmployeeService $employeeService
    ) {}

    /**
     * List all applications, optionally filtered by posting.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', JobApplication::class);
        $applications = $this->jobApplicationService->getPaginatedList([
            'posting_id' => $request->posting_id,
            'status' => $request->status,
        ], 20);

        $postings = $this->jobPostingService->getActivePostings();

        return view('recruitment::applications.index', compact('applications', 'postings'));
    }

    /**
     * Show a single application with timeline & interview history.
     */
    public function show(JobApplication $application)
    {
        $this->authorize('view', $application);
        $application->load('jobPosting', 'interviews.interviewer');
        $employees = $this->employeeService->getActiveEmployees();

        return view('recruitment::applications.show', compact('application', 'employees'));
    }

    /**
     * Update the status of an application (move through pipeline).
     */
    public function updateStatus(UpdateJobApplicationStatusRequest $request, JobApplication $application)
    {
        $this->authorize('update', $application);

        $dto = UpdateJobApplicationStatusData::fromRequest($request->validated());
        $this->jobApplicationService->updateStatus($application, $dto);

        return back()->with('success', 'Candidate status updated to ' . ucwords(str_replace('_', ' ', $dto->status)) . '.');
    }

    /**
     * Schedule an interview for an application.
     */
    public function scheduleInterview(ScheduleInterviewRequest $request, JobApplication $application)
    {
        $this->authorize('update', $application);

        $dto = ScheduleInterviewData::fromRequest($request->validated());
        $this->jobApplicationService->scheduleInterview($application, $dto);

        return back()->with('success', 'Interview scheduled successfully.');
    }

    /**
     * Update interview result (feedback).
     */
    public function updateInterview(UpdateInterviewRequest $request, Interview $interview)
    {
        $this->authorize('update', $interview->application ?? JobApplication::class);

        $dto = UpdateInterviewData::fromRequest($request->validated());
        $this->jobApplicationService->updateInterview($interview, $dto);

        return back()->with('success', 'Interview updated.');
    }

    /**
     * Redirect to Employee Create form with candidate data.
     */
    public function hire(JobApplication $application)
    {
        $this->authorize('update', $application);
        
        $this->jobApplicationService->hire($application);

        return redirect()->route('hr.employees.create', [
            'first_name'      => $application->first_name,
            'last_name'       => $application->last_name,
            'email'           => $application->email,
            'phone'           => $application->phone,
            'employment_type' => $application->jobPosting->employment_type ?? 'full_time',
            'department_id'   => $application->jobPosting->department_id ?? null,
        ])->with('success', 'Candidate marked as Hired! Please complete the employee profile.');
    }
}
