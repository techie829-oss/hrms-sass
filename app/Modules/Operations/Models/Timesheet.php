<?php

namespace App\Modules\Operations\Models;

use App\Core\Traits\BelongsToTenant;
use App\Core\Traits\HasDynamicSchema;
use App\Modules\HR\Models\Employee;
use App\Modules\Operations\Models\Project;
use App\Modules\Operations\Models\Task;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Timesheet extends Model
{
    use BelongsToTenant, HasDynamicSchema, HasFactory;

    protected $fillable = [
        'tenant_id',
        'employee_id',
        'project_id',
        'task_id',
        'date',
        'start_time',
        'end_time',
        'hours',
        'description',
        'status',
        'approved_by',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'hours' => 'decimal:2',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'approved_by');
    }
}
