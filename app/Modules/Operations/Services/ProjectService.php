<?php

namespace App\Modules\Operations\Services;

use App\Modules\Operations\Models\Project;
use App\Modules\Operations\DTOs\ProjectData;

class ProjectService
{
    public function createProject(ProjectData $data): Project
    {
        return Project::create($data->toArray());
    }

    public function updateProject(Project $project, ProjectData $data): Project
    {
        $project->update($data->toArray());
        return $project;
    }

    public function deleteProject(Project $project): void
    {
        $project->delete();
    }
}
