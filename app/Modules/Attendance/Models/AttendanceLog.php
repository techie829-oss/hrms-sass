<?php

namespace App\Modules\Attendance\Models;

use App\Core\Traits\BelongsToTenant;
use App\Core\Traits\HasDynamicSchema;
use App\Modules\HR\Models\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Modules\Attendance\Models\AttendanceShift;

class AttendanceLog extends Model
{
    use BelongsToTenant, HasDynamicSchema, HasFactory, LogsActivity;

    protected $fillable = [
        'tenant_id',
        'employee_id',
        'attendance_shift_id',
        'date',
        'check_in',
        'check_out',
        'worked_hours',
        'status',
        'is_late',
        'is_early_leave',
        'late_minutes',
        'early_leave_minutes',
        'overtime_minutes',
        'check_in_ip',
        'check_out_ip',
        'check_in_lat',
        'check_in_lng',
        'check_out_lat',
        'check_out_lng',
        'check_in_photo',
        'check_out_photo',
        'remarks',
        'approved_by',
    ];

    protected $casts = [
        'date' => 'date',
        'check_in' => 'datetime:H:i',
        'check_out' => 'datetime:H:i',
        'worked_hours' => 'decimal:2',
        'is_late' => 'boolean',
        'is_early_leave' => 'boolean',
        'check_in_lat' => 'decimal:7',
        'check_in_lng' => 'decimal:7',
        'check_out_lat' => 'decimal:7',
        'check_out_lng' => 'decimal:7',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(AttendanceShift::class, 'attendance_shift_id');
    }
}
