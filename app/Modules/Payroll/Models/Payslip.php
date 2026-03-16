<?php

namespace App\Modules\Payroll\Models;

use App\Core\Traits\BelongsToTenant;
use App\Modules\HR\Models\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payslip extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'payroll_run_id',
        'employee_id',
        'payslip_number',
        'month',
        'year',
        'working_days',
        'present_days',
        'absent_days',
        'leave_days',
        'holidays',
        'basic_salary',
        'gross_earnings',
        'total_deductions',
        'net_salary',
        'earnings_breakdown',
        'deductions_breakdown',
        'status',
        'payment_date',
        'payment_mode',
        'transaction_ref',
    ];

    protected $casts = [
        'month' => 'integer',
        'year' => 'integer',
        'working_days' => 'integer',
        'present_days' => 'integer',
        'absent_days' => 'integer',
        'leave_days' => 'integer',
        'holidays' => 'integer',
        'basic_salary' => 'decimal:2',
        'gross_earnings' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'earnings_breakdown' => 'json',
        'deductions_breakdown' => 'json',
        'payment_date' => 'date',
    ];

    public function payrollRun()
    {
        return $this->belongsTo(PayrollRun::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
