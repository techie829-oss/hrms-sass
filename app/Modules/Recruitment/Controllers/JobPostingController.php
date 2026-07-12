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

    public function show($id)
    {
        $posting = $this->jobPostingService->findOrFail($id);
        $posting->load('applications');
        return view('recruitment::job_postings.show', compact('posting'));
    }

    public function edit($id)
    {
        $posting = $this->jobPostingService->findOrFail($id);
        return view('recruitment::job_postings.edit', compact('posting'));
    }

    public function update(UpdateJobPostingRequest $request, $id)
    {
        $dto = JobPostingData::fromUpdateRequest($request->validated());
        $this->jobPostingService->updatePosting($id, $dto);

        return redirect()->route('recruitment.job_postings.show', $id)->with('success', 'Job posting updated successfully.');
    }

    public function destroy($id)
    {
        $this->jobPostingService->delete($id);
        return redirect()->route('recruitment.job_postings.index')->with('success', 'Job posting deleted successfully.');
    }
}
