<?php

namespace App\Modules\Operations\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Operations\Models\Project;
use App\Modules\Operations\Models\Client;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::where('tenant_id', tenant('id'))
            ->with(['client', 'tasks'])
            ->latest()
            ->paginate(10);

        return view('operations::projects.index', compact('projects'));
    }

    public function create()
    {
        $clients = Client::where('tenant_id', tenant('id'))->get();
        return view('operations::projects.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'client_id' => 'nullable|exists:clients,id',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'deadline' => 'nullable|date',
            'budget' => 'nullable|numeric',
        ]);

        $validated['tenant_id'] = tenant('id');
        $project = Project::create($validated);

        return redirect()->route('operations.projects.show', $project)
            ->with('success', 'Project created successfully.');
    }

    public function show(Project $project)
    {
        $project->load(['client', 'tasks.assignee', 'timesheets.employee']);
        return view('operations::projects.show', compact('project'));
    }
}
