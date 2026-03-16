<?php

namespace App\Modules\Leave\Models;

use App\Core\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'code',
        'max_days_per_year',
        'is_paid',
        'is_carry_forward',
        'max_carry_forward_days',
        'is_half_day_allowed',
        'is_negative_balance_allowed',
        'requires_approval',
        'min_days_notice',
        'applicable_gender',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'is_carry_forward' => 'boolean',
        'is_half_day_allowed' => 'boolean',
        'is_negative_balance_allowed' => 'boolean',
        'requires_approval' => 'boolean',
        'is_active' => 'boolean',
        'max_days_per_year' => 'integer',
        'max_carry_forward_days' => 'integer',
        'min_days_notice' => 'integer',
    ];
}
