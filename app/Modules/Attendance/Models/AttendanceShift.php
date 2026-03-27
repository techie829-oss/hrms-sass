<?php

namespace App\Modules\Attendance\Models;

use App\Core\Traits\BelongsToTenant;
use App\Core\Traits\HasDynamicSchema;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class AttendanceShift extends Model
{
    use BelongsToTenant, HasDynamicSchema, HasFactory, LogsActivity;

    protected $fillable = [
        'tenant_id',
        'name',
        'start_time',
        'end_time',
        'grace_minutes',
        'half_day_hours',
        'is_overnight',
        'is_default',
        'is_active',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_overnight' => 'boolean',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }
}
