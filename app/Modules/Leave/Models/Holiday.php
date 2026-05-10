<?php

namespace App\Modules\Leave\Models;

use App\Core\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'date',
        'is_optional',
        'description',
    ];

    protected $casts = [
        'date' => 'date',
        'is_optional' => 'boolean',
    ];
}
