<?php

namespace App\Modules\Operations\Services;

use App\Modules\Operations\Models\Task;
use App\Modules\Operations\DTOs\TaskData;

class TaskService
{
    public function createTask(TaskData $data): Task
    {
        return Task::create($data->toArray());
    }

    public function updateTask(Task $task, TaskData $data): Task
    {
        $task->update($data->toArray());
        return $task;
    }

    public function deleteTask(Task $task): void
    {
        $task->delete();
    }
}
