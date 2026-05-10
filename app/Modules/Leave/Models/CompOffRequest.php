<?php

namespace App\Modules\Leave\Models;

use App\Core\Traits\BelongsToTenant;
use App\Modules\HR\Models\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompOffRequest extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'employee_id',
        'worked_on_date',
        'duration',
        'reason',
        'status',
        'approved_by',
        'approved_at',
        'expiry_date',
        'leave_request_id',
        'is_used',
        'used_at',
    ];

    protected $casts = [
        'worked_on_date' => 'date',
        'duration' => 'decimal:1',
        'approved_at' => 'datetime',
        'expiry_date' => 'date',
        'is_used' => 'boolean',
        'used_at' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveRequest()
    {
        return $this->belongsTo(LeaveRequest::class);
    }
}
