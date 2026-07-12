<?php

namespace App\Modules\Leave\Services;

use App\Modules\Leave\Models\Holiday;
use App\Modules\Leave\DTOs\HolidayData;

use App\Modules\Leave\Interfaces\HolidayRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class HolidayService
{
    public function __construct(
        protected HolidayRepositoryInterface $repository
    ) {}

    public function getAllOrderedByDate(): Collection
    {
        return $this->repository->getAllOrderedByDate();
    }

    public function createHoliday(HolidayData $data): Holiday
    {
        return $this->repository->create([
            'name' => $data->name,
            'date' => $data->date,
            'is_optional' => $data->is_optional,
            'description' => $data->description,
            'tenant_id' => $data->tenant_id,
        ]);
    }

    public function deleteHoliday(Holiday $holiday): void
    {
        $this->repository->delete($holiday->id);
    }
}
