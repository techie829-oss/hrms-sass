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
        'share_key',
        'status',
        'closing_date',
    ];

    protected $casts = [
        'closing_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($jobPosting) {
            if (empty($jobPosting->share_key)) {
                $jobPosting->share_key = \Illuminate\Support\Str::random(32);
            }
        });
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }
}
