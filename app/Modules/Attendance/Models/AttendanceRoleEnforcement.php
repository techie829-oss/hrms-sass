<?php

namespace App\Modules\Attendance\Models;

use App\Core\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRoleEnforcement extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'role_name',
        'checkin_required',
        'allow_multi_clocking',
    ];

    protected $casts = [
        'allow_multi_clocking' => 'boolean',
    ];
}
