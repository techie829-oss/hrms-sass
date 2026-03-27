<?php

namespace App\Modules\HR\Models;

use App\Core\Traits\BelongsToTenant;
use App\Core\Traits\HasDynamicSchema;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use BelongsToTenant, HasDynamicSchema, HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'code',
        'description',
        'head_employee_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function head()
    {
        return $this->belongsTo(Employee::class, 'head_employee_id');
    }
}
