<?php

namespace App\Modules\Leave\Models;

use App\Core\Traits\BelongsToTenant;
use App\Modules\HR\Models\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveBalance extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'employee_id',
        'leave_type_id',
        'year',
        'total_allocated',
        'total_used',
        'total_pending',
        'carried_forward',
        'balance',
    ];

    protected $casts = [
        'year' => 'integer',
        'total_allocated' => 'decimal:1',
        'total_used' => 'decimal:1',
        'total_pending' => 'decimal:1',
        'carried_forward' => 'decimal:1',
        'balance' => 'decimal:1',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }
}
