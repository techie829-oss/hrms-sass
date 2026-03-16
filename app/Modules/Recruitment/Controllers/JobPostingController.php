<?php

namespace App\Modules\Recruitment\Controllers;

use App\Core\BaseController;
use App\Modules\Recruitment\Services\JobPostingService;
use Illuminate\Http\Request;

class JobPostingController extends BaseController
{
    public function __construct(
        protected JobPostingService $jobPostingService
    ) {}

    public function index()
    {
        $postings = $this->jobPostingService->all();
        return view('modules.recruitment.job_postings.index', compact('postings'));
    }

    public function create()
    {
        return view('modules.recruitment.job_postings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'employment_type' => 'required|string|in:full_time,part_time,contract,intern',
            'status' => 'required|string|in:draft,open,closed',
            'salary_range' => 'nullable|string|max:255',
            'closing_date' => 'nullable|date',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
        ]);

        $this->jobPostingService->create($validated);

        return redirect()->route('recruitment.job_postings.index')->with('success', 'Job posting created successfully.');
    }

    public function show($id)
    {
        $posting = $this->jobPostingService->findOrFail($id);
        $posting->load('applications');
        return view('modules.recruitment.job_postings.show', compact('posting'));
    }

    public function edit($id)
    {
        $posting = $this->jobPostingService->findOrFail($id);
        return view('modules.recruitment.job_postings.edit', compact('posting'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'employment_type' => 'required|string|in:full_time,part_time,contract,intern',
            'status' => 'required|string|in:draft,open,closed',
            'salary_range' => 'nullable|string|max:255',
            'closing_date' => 'nullable|date',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
        ]);

        $this->jobPostingService->update($id, $validated);

        return redirect()->route('recruitment.job_postings.show', $id)->with('success', 'Job posting updated successfully.');
    }

    public function destroy($id)
    {
        $this->jobPostingService->delete($id);
        return redirect()->route('recruitment.job_postings.index')->with('success', 'Job posting deleted successfully.');
    }
}
