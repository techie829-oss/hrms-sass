<?php

namespace App\Modules\Recruitment\Controllers\Public;

use App\Core\BaseController;
use App\Modules\Recruitment\Models\JobPosting;
use App\Modules\Recruitment\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PublicCareerController extends BaseController
{
    /**
     * Display a listing of active job postings for the public.
     */
    public function index()
    {
        $tenant = tenant();
        
        $postings = JobPosting::where('status', 'open')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('recruitment::public.index', compact('postings', 'tenant'));
    }

    /**
     * Display the specified job posting and application form.
     */
    public function show($hash)
    {
        $job_posting = JobPosting::where('share_key', $hash)->firstOrFail();

        if ($job_posting->status !== 'open') {
            abort(404);
        }

        $tenant = tenant();

        return view('recruitment::public.show', compact('job_posting', 'tenant', 'hash'));
    }

    /**
     * Store a newly created job application in storage.
     */
    public function store(Request $request, $hash)
    {
        $job_posting = JobPosting::where('share_key', $hash)->firstOrFail();

        if ($job_posting->status !== 'open') {
            abort(404);
        }

        $validated = $request->validate([
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'phone'        => 'nullable|string|max:20',
            'cover_letter' => 'nullable|string',
            'resume'       => 'nullable|file|mimes:pdf,doc,docx|max:5120', // 5MB max
        ]);

        $resumePath = null;
        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('resumes/' . tenant('id'), 'public');
        }

        $application = new JobApplication([
            'job_posting_id' => $job_posting->id,
            'first_name'     => $validated['first_name'],
            'last_name'      => $validated['last_name'],
            'email'          => $validated['email'],
            'phone'          => $validated['phone'] ?? null,
            'cover_letter'   => $validated['cover_letter'] ?? null,
            'resume_path'    => $resumePath,
            'status'         => 'new',
            'applied_at'     => Carbon::now(),
        ]);
        
        $application->tenant_id = tenant('id');
        $application->save();

        return redirect()->route('tenant.careers.show', ['job_posting' => $hash])
            ->with('success', 'Your application has been submitted successfully! We will be in touch soon.');
    }
}
