<?php

namespace App\Core\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant
{
    public static function bootBelongsToTenant(): void
    {
        static::creating(function ($model) {
            $tenant = function_exists('saas_tenant') ? saas_tenant() : null;
            if ($tenant && !$model->tenant_id) {
                $model->tenant_id = $tenant->id;
            }
        });

        static::addGlobalScope('tenant', function (Builder $builder) {
            $tenant = function_exists('saas_tenant') ? saas_tenant() : null;
            if ($tenant) {
                $builder->where($builder->getModel()->getTable() . '.tenant_id', $tenant->id);
            }
        });
    }
}
