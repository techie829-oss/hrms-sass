<?php

namespace App\Modules\Attendance\Models;

use App\Core\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceEmployeeEnforcement extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'employee_id',
        'enforce_kiosk',
        'multi_clocking',
        'is_flexible',   // Per-employee: ignore late/early leave, only count min hours
    ];

    protected $casts = [
        'enforce_kiosk'  => 'integer',
        'multi_clocking' => 'integer',
        'is_flexible'    => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(\App\Modules\HR\Models\Employee::class);
    }
}
