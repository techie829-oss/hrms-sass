<?php

namespace App\Modules\Operations\Controllers;

use App\Core\BaseController;
use App\Modules\Operations\Models\Project;
use App\Modules\Operations\Models\Client;
use Illuminate\Http\Request;
use App\Modules\Operations\Requests\StoreProjectRequest;
use App\Modules\Operations\DTOs\ProjectData;
use App\Modules\Operations\Services\ProjectService;

class ProjectController extends BaseController
{
    public function __construct(
        protected ProjectService $projectService
    ) {
        $this->authorizeResource(Project::class, 'project');
    }

    public function index()
    {
        $projects = Project::where('tenant_id', saas_tenant('id'))
            ->with(['client', 'tasks'])
            ->latest()
            ->paginate(10);

        return view('operations::projects.index', compact('projects'));
    }

    public function create()
    {
        $clients = Client::where('tenant_id', saas_tenant('id'))->get();
        return view('operations::projects.create', compact('clients'));
    }

    public function store(StoreProjectRequest $request)
    {
        $dto = ProjectData::fromArray($request->validated(), saas_tenant('id'));
        $project = $this->projectService->createProject($dto);

        return redirect()->route('operations.projects.show', $project)
            ->with('success', 'Project created successfully.');
    }

    public function show(Project $project)
    {
        $project->load(['client', 'tasks.assignee', 'timesheets.employee']);
        return view('operations::projects.show', compact('project'));
    }
}
