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
        'role_id',
        'enforce_kiosk',
        'multi_clocking',
    ];

    protected $casts = [
        'enforce_kiosk' => 'integer',
        'multi_clocking' => 'integer',
    ];
}
