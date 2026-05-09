<?php

namespace App\Modules\HR\Models;

use App\Core\Traits\BelongsToTenant;
use App\Core\Traits\HasDynamicSchema;
use Illuminate\Database\Eloquent\Model;

class EmployeeDocument extends Model
{
    use BelongsToTenant, HasDynamicSchema;

    protected $table = 'employee_documents';

    protected $fillable = [
        'tenant_id',
        'employee_id',
        'title',
        'document_type',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
        'expiry_date',
        'notes',
        'is_verified',
        'uploaded_by',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'is_verified' => 'boolean',
        'file_size' => 'integer',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
