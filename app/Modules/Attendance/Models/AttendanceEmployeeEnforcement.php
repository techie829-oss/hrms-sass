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
    ];

    protected $casts = [
        'enforce_kiosk' => 'integer',
        'multi_clocking' => 'integer',
    ];

    public function employee()
    {
        return $this->belongsTo(\App\Modules\HR\Models\Employee::class);
    }
}
