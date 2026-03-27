<?php

namespace App\Modules\HR\Models;

use App\Core\Traits\BelongsToTenant;
use App\Core\Traits\HasDynamicSchema;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Employee extends Model
{
    use BelongsToTenant, HasDynamicSchema, HasFactory, LogsActivity;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'employee_id',
        'department_id',
        'designation_id',
        'attendance_shift_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'date_of_joining',
        'status',
        'employment_type',
        'salary',
        'profile_photo',
        'cover_photo',
        'main_image',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'date_of_joining' => 'date',
        'salary' => 'decimal:2',
        'basic_salary' => 'decimal:2',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function attendanceShift()
    {
        return $this->belongsTo(\App\Modules\Attendance\Models\AttendanceShift::class);
    }

    public function reportingTo()
    {
        return $this->belongsTo(Employee::class, 'reporting_to');
    }

    public function appraisals()
    {
        return $this->hasMany(\App\Modules\Performance\Models\Appraisal::class);
    }

    public function attendanceLogs()
    {
        return $this->hasMany(\App\Modules\Attendance\Models\AttendanceLog::class);
    }

    public function goals()
    {
        return $this->hasMany(\App\Modules\Performance\Models\Goal::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
