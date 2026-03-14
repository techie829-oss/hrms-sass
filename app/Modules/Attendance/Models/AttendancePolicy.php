<?php

namespace App\Modules\Attendance\Models;

use App\Core\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class AttendancePolicy extends Model
{
    use BelongsToTenant, HasFactory, LogsActivity;

    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'late_mark_after_minutes',
        'early_leave_before_minutes',
        'min_hours_full_day',
        'min_hours_half_day',
        'auto_deduct_leave',
        'max_late_allowed_per_month',
        'is_default',
        'is_active',
    ];

    protected $casts = [
        'auto_deduct_leave' => 'boolean',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }
}
