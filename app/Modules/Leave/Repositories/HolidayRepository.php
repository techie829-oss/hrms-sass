<?php

namespace App\Modules\Leave\Repositories;

use App\Core\BaseRepository;
use App\Modules\Leave\Interfaces\HolidayRepositoryInterface;
use App\Modules\Leave\Models\Holiday;
use Illuminate\Database\Eloquent\Collection;

class HolidayRepository extends BaseRepository implements HolidayRepositoryInterface
{
    public function __construct(Holiday $model)
    {
        $this->model = $model;
    }

    public function getAllOrderedByDate(): Collection
    {
        return $this->model->orderBy('date')->get();
    }
}
