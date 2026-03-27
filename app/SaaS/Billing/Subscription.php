<?php

namespace App\SaaS\Billing;

use App\Core\Traits\UsesPublicSchema;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use UsesPublicSchema;
    protected $table = 'tenant_subscriptions';

    protected $fillable = [
        'tenant_id',
        'plan_id',
        'razorpay_id',
        'razorpay_payment_id',
        'razorpay_signature',
        'status',
        'trial_ends_at',
        'starts_at',
        'ends_at',
        'cancelled_at',
        'meta',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'meta' => 'array',
    ];

    /**
     * Get the plan for this subscription.
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id', 'slug');
    }

    /**
     * Get the tenant for this subscription.
     */
    public function saas_tenant()
    {
        return $this->belongsTo(\App\Models\Tenant::class);
    }

    /**
     * Check if subscription is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && ($this->ends_at === null || $this->ends_at->isFuture());
    }

    /**
     * Check if subscription is on trial.
     */
    public function onTrial(): bool
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    /**
     * Check if subscription is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->cancelled_at !== null;
    }
}
