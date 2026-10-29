<?php

namespace App\Modules\Recruitment\Controllers;

use App\Core\BaseController;
use App\Modules\Recruitment\Services\JobPostingService;
use Illuminate\Http\Request;
use App\Modules\Recruitment\Requests\StoreJobPostingRequest;
use App\Modules\Recruitment\Requests\UpdateJobPostingRequest;
use App\Modules\Recruitment\DTOs\JobPostingData;
use App\Modules\Recruitment\Models\JobPosting;

class JobPostingController extends BaseController
{
    public function __construct(
        protected JobPostingService $jobPostingService
    ) {
        $this->authorizeResource(JobPosting::class, 'job_posting');
    }

    public function index()
    {
        $postings = $this->jobPostingService->all();
        return view('recruitment::job_postings.index', compact('postings'));
    }

    public function create()
    {
        return view('recruitment::job_postings.create');
    }

    public function store(StoreJobPostingRequest $request)
    {
        $dto = JobPostingData::fromStoreRequest($request->validated());
        $this->jobPostingService->createPosting($dto);

        return redirect()->route('recruitment.job_postings.index')->with('success', 'Job posting created successfully.');
    }

    public function show(JobPosting $job_posting)
    {
        $posting = $job_posting;
        $posting->load('applications');
        return view('recruitment::job_postings.show', compact('posting'));
    }

    public function edit(JobPosting $job_posting)
    {
        $posting = $job_posting;
        return view('recruitment::job_postings.edit', compact('posting'));
    }

    public function update(UpdateJobPostingRequest $request, JobPosting $job_posting)
    {
        $dto = JobPostingData::fromUpdateRequest($request->validated());
        $this->jobPostingService->updatePosting($job_posting->id, $dto);

        return redirect()->route('recruitment.job_postings.show', $job_posting->id)->with('success', 'Job posting updated successfully.');
    }

    public function destroy(JobPosting $job_posting)
    {
        $this->jobPostingService->delete($job_posting->id);
        return redirect()->route('recruitment.job_postings.index')->with('success', 'Job posting deleted successfully.');
    }
}
