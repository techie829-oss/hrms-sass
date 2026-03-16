<?php

namespace App\Modules\Payroll\Models;

use App\Core\Traits\BelongsToTenant;
use App\Modules\HR\Models\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryStructure extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'employee_id',
        'ctc',
        'gross_salary',
        'net_salary',
        'earnings',
        'deductions',
        'effective_from',
        'effective_to',
        'is_active',
    ];

    protected $casts = [
        'ctc' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'earnings' => 'json',
        'deductions' => 'json',
        'effective_from' => 'date',
        'effective_to' => 'date',
        'is_active' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
