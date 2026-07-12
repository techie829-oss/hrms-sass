<?php

namespace App\Modules\Recruitment\Controllers\Public;

use App\Core\BaseController;
use App\Modules\Recruitment\Models\JobPosting;
use App\Modules\Recruitment\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

use App\Modules\Recruitment\Requests\StorePublicJobApplicationRequest;
use App\Modules\Recruitment\DTOs\PublicJobApplicationData;
use App\Modules\Recruitment\Services\JobPostingService;
use App\Modules\Recruitment\Services\JobApplicationService;

class PublicCareerController extends BaseController
{
    public function __construct(
        protected JobPostingService $jobPostingService,
        protected JobApplicationService $jobApplicationService
    ) {}
    /**
     * Display a listing of active job postings for the public.
     */
    public function index()
    {
        $tenant = saas_tenant();
        
        $postings = $this->jobPostingService->getActivePostings();

        return view('recruitment::public.index', compact('postings', 'tenant'));
    }

    /**
     * Display the specified job posting and application form.
     */
    public function show($hash)
    {
        // In a real scenario we'd query by share_key in repository
        // For now we can find by share_key or use a new method
        $job_posting = JobPosting::where('share_key', $hash)->firstOrFail();

        if ($job_posting->status !== 'open') {
            abort(404);
        }

        $tenant = saas_tenant();

        return view('recruitment::public.show', compact('job_posting', 'tenant', 'hash'));
    }

    /**
     * Store a newly created job application in storage.
     */
    public function store(StorePublicJobApplicationRequest $request, $hash)
    {
        $job_posting = JobPosting::where('share_key', $hash)->firstOrFail();

        if ($job_posting->status !== 'open') {
            abort(404);
        }

        $validated = $request->validated();
        $dto = PublicJobApplicationData::fromRequest($validated);

        $resumePath = null;
        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('resumes/' . saas_tenant('id'), 'public');
        }

        $this->jobApplicationService->storePublicApplication($dto, $job_posting->id, $resumePath);

        return redirect()->route('tenant.careers.show', ['job_posting' => $hash])
            ->with('success', 'Your application has been submitted successfully! We will be in touch soon.');
    }
}
