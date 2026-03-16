<?php

namespace App\Modules\Recruitment\Models;

use App\Core\Traits\BelongsToTenant;
use App\Modules\HR\Models\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'job_application_id',
        'interviewer_id',
        'interview_date',
        'location',
        'type',
        'status',
        'feedback',
    ];

    protected $casts = [
        'interview_date' => 'datetime',
    ];

    public function application()
    {
        return $this->belongsTo(JobApplication::class, 'job_application_id');
    }

    public function interviewer()
    {
        return $this->belongsTo(Employee::class, 'interviewer_id');
    }
}
