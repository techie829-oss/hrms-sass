<?php

namespace App\Modules\Performance\Models;

use App\Core\Traits\BelongsToTenant;
use App\Modules\HR\Models\Department;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KPI extends Model
{
    use BelongsToTenant;

    protected $table = 'kpis';

    protected $fillable = [
        'name',
        'description',
        'department_id',
        'target_value',
        'unit',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
