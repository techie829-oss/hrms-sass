<?php

namespace App\Core\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant
{
    public static function bootBelongsToTenant(): void
    {
        static::creating(function ($model) {
            if (tenant('id') && ! $model->tenant_id) {
                $model->tenant_id = tenant('id');
            }
        });

        static::addGlobalScope('tenant', function (Builder $builder) {
            if (tenant('id')) {
                $builder->where('tenant_id', tenant('id'));
            }
        });
    }
}
