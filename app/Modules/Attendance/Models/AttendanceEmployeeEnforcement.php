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
        'checkin_required',
        'allow_multi_clocking',
    ];

    protected $casts = [
        'allow_multi_clocking' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(\App\Modules\HR\Models\Employee::class);
    }
}
