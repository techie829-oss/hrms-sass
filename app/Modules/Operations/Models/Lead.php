<?php

namespace App\Modules\Operations\Models;

use App\Core\Traits\BelongsToTenant;
use App\Modules\HR\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lead extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'phone',
        'company_name',
        'source',
        'status',
        'assigned_to',
        'description',
    ];

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'assigned_to');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
