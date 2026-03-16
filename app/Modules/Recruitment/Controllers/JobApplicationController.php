<?php

namespace App\Modules\Recruitment\Controllers;

use App\Core\BaseController;
use App\Modules\Recruitment\Models\JobApplication;
use App\Modules\Recruitment\Models\JobPosting;
use App\Modules\Recruitment\Models\Interview;
use App\Modules\HR\Models\Employee;
use Illuminate\Http\Request;

class JobApplicationController extends BaseController
{
    /**
     * List all applications, optionally filtered by posting.
     */
    public function index(Request $request)
    {
        $query = JobApplication::with('jobPosting')
            ->when($request->posting_id, fn($q) => $q->where('job_posting_id', $request->posting_id))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest('applied_at');

        $applications = $query->paginate(20)->withQueryString();
        $postings = JobPosting::orderBy('title')->get();

        return view('modules.recruitment.applications.index', compact('applications', 'postings'));
    }

    /**
     * Show a single application with timeline & interview history.
     */
    public function show(JobApplication $application)
    {
        $application->load('jobPosting', 'interviews.interviewer');
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();

        return view('modules.recruitment.applications.show', compact('application', 'employees'));
    }

    /**
     * Update the status of an application (move through pipeline).
     */
    public function updateStatus(Request $request, JobApplication $application)
    {
        $request->validate([
            'status' => 'required|in:new,reviewing,shortlisted,interview,offered,hired,rejected',
            'notes'  => 'nullable|string|max:1000',
        ]);

        $application->update(['status' => $request->status]);

        return back()->with('success', 'Candidate status updated to ' . ucwords(str_replace('_', ' ', $request->status)) . '.');
    }

    /**
     * Schedule an interview for an application.
     */
    public function scheduleInterview(Request $request, JobApplication $application)
    {
        $validated = $request->validate([
            'interview_date' => 'required|date|after:now',
            'type'           => 'required|in:phone,video,in_person,technical',
            'location'       => 'nullable|string|max:255',
            'interviewer_id' => 'nullable|exists:employees,id',
        ]);

        Interview::create(array_merge($validated, [
            'job_application_id' => $application->id,
            'status'             => 'scheduled',
        ]));

        // Auto-move status to interview stage
        if ($application->status === 'shortlisted' || $application->status === 'reviewing') {
            $application->update(['status' => 'interview']);
        }

        return back()->with('success', 'Interview scheduled successfully.');
    }

    /**
     * Update interview result (feedback).
     */
    public function updateInterview(Request $request, Interview $interview)
    {
        $request->validate([
            'status'   => 'required|in:scheduled,completed,cancelled,no_show',
            'feedback' => 'nullable|string',
        ]);

        $interview->update($request->only('status', 'feedback'));

        return back()->with('success', 'Interview updated.');
    }
}
