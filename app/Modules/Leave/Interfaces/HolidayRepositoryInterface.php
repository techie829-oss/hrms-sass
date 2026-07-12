<?php

namespace App\Modules\Leave\Interfaces;

use App\Core\BaseRepositoryInterface;
use App\Modules\Leave\Models\Holiday;
use Illuminate\Database\Eloquent\Collection;

interface HolidayRepositoryInterface extends BaseRepositoryInterface
{
    public function getAllOrderedByDate(): Collection;
}
