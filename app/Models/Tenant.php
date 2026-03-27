<?php

namespace App\Models;

use App\Core\Traits\UsesPublicSchema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tenant extends Model
{
    use UsesPublicSchema;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'slug',
        'domain',
        'schema',
        'mode',
        'plan_id',
        'status',
        'email',
        'contact_no',
    ];

    protected static function booted(): void
    {
        static::creating(function ($tenant) {
            if (!$tenant->id) {
                $tenant->id = (string) Str::uuid();
            }
            
            if (!$tenant->schema) {
                $tenant->schema = ($tenant->mode === 'dedicated') 
                    ? 'tenant_' . ($tenant->id) 
                    : 'shared';
            }
        });
    }

    /**
     * Get the domains for this tenant.
     */
    public function domains()
    {
        return $this->hasMany(Domain::class, 'tenant_id');
    }

    /**
     * Get the subscription for this tenant.
     */
    public function subscription()
    {
        return $this->hasOne(\App\SaaS\Billing\Subscription::class, 'tenant_id');
    }
}
