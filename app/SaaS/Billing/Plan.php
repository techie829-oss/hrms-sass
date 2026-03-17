<?php

namespace App\SaaS\Billing;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'razorpay_plan_id',
        'description',
        'price_monthly',
        'price_yearly',
        'max_employees',
        'max_modules',
        'features',
        'is_active',
    ];

    protected $casts = [
        'price_monthly' => 'decimal:2',
        'price_yearly' => 'decimal:2',
        'max_employees' => 'integer',
        'max_modules' => 'integer',
        'features' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get all active plans.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if this plan is free.
     */
    public function isFree(): bool
    {
        return $this->price_monthly == 0;
    }
}
