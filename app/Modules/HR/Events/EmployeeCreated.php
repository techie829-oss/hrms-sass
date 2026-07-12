<?php

namespace App\Modules\HR\Events;

use App\Modules\HR\Models\Employee;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EmployeeCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(public Employee $employee) {}
}
