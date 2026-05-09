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
        'personal_email',
        'country_code',
        'phone',
        'alt_country_code',
        'alt_phone',
        'gender',
        'date_of_birth',
        'date_of_joining',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
        'current_address',
        'permanent_address',
        'status',
        'employment_type',
        'salary',
        'basic_salary',
        'profile_photo',
        'cover_photo',
        'main_image',
        'pan_number',
        'aadhar_number',
        'passport_number',
        'reporting_to',
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

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function todayAttendance()
    {
        return $this->hasOne(\App\Modules\Attendance\Models\AttendanceLog::class)->where('date', now()->toDateString());
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

    public function documents()
    {
        return $this->hasMany(EmployeeDocument::class);
    }

    public function attendanceLogs()
    {
        return $this->hasMany(\App\Modules\Attendance\Models\AttendanceLog::class);
    }

    public function goals()
    {
        return $this->hasMany(\App\Modules\Performance\Models\Goal::class);
    }

    public function bankAccounts()
    {
        return $this->hasMany(EmployeeBankAccount::class);
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
