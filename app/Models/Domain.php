<?php

namespace App\Models;

use App\Core\Traits\UsesPublicSchema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Domain extends Model
{
    use UsesPublicSchema;

    protected $fillable = [
        'domain',
        'tenant_id',
    ];

    /**
     * Get the tenant that owns the domain.
     */
    public function saas_tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
