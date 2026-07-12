<?php

namespace App\Modules\Operations\Services;

use App\Modules\Operations\Models\Timesheet;
use App\Modules\Operations\DTOs\TimesheetData;

class TimesheetService
{
    public function createTimesheet(TimesheetData $data): Timesheet
    {
        return Timesheet::create([
            'project_id' => $data->project_id,
            'task_id' => $data->task_id,
            'date' => $data->date,
            'start_time' => $data->start_time,
            'end_time' => $data->end_time,
            'hours' => $data->hours,
            'description' => $data->description,
            'employee_id' => $data->employee_id,
            'tenant_id' => $data->tenant_id,
        ]);
    }
}
