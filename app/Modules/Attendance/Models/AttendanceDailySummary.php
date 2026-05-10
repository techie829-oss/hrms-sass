<?php

namespace App\Modules\Attendance\Models;

use App\Core\Traits\BelongsToTenant;
use App\Core\Traits\HasDynamicSchema;
use App\Modules\HR\Models\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceDailySummary extends Model
{
    use BelongsToTenant, HasDynamicSchema, HasFactory;

    protected $fillable = [
        'tenant_id',
        'employee_id',
        'date',
        'first_check_in',
        'last_check_out',
        'total_worked_hours',
        'total_sessions',
        'day_type',
        'tags',
        'late_minutes',
        'overtime_minutes',
        'early_leave_minutes',
        'status',
    ];

    protected $casts = [
        'date'               => 'date',
        'first_check_in'     => 'datetime',
        'last_check_out'     => 'datetime',
        'total_worked_hours' => 'decimal:2',
        'tags'               => 'array',  // Auto JSON encode/decode
    ];

    // -------------------------------------------------------
    // Relationships
    // -------------------------------------------------------

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    // -------------------------------------------------------
    // Computed Helpers
    // -------------------------------------------------------

    /**
     * Check if a specific tag exists.
     */
    public function hasTag(string $tag): bool
    {
        return in_array($tag, $this->tags ?? []);
    }

    /**
     * Return formatted worked hours string (e.g. "7h 30m").
     */
    public function getFormattedHoursAttribute(): string
    {
        $hours = (int) $this->total_worked_hours;
        $minutes = (int) round(($this->total_worked_hours - $hours) * 60);
        return $minutes > 0 ? "{$hours}h {$minutes}m" : "{$hours}h";
    }
}
