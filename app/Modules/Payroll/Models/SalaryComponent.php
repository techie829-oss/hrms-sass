<?php

namespace App\Modules\Payroll\Models;

use App\Core\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryComponent extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'code',
        'type',
        'calculation_type',
        'default_amount',
        'percentage_of',
        'percentage_base',
        'is_taxable',
        'is_mandatory',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'default_amount' => 'decimal:2',
        'percentage_of' => 'decimal:2',
        'is_taxable' => 'boolean',
        'is_mandatory' => 'boolean',
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];
}
