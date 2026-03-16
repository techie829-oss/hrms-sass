<?php

namespace App\Modules\Recruitment\Models;

use App\Core\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPosting extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'title',
        'description',
        'requirements',
        'location',
        'employment_type',
        'salary_range',
        'status',
        'closing_date',
    ];

    protected $casts = [
        'closing_date' => 'date',
    ];

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }
}
