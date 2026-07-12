<?php

namespace App\Modules\Leave\Controllers;

use App\Core\BaseController;
use App\Modules\Leave\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Modules\Leave\Requests\StoreHolidayRequest;
use App\Modules\Leave\DTOs\HolidayData;
use App\Modules\Leave\Services\HolidayService;

class HolidayController extends BaseController
{
    public function __construct(
        protected HolidayService $holidayService
    ) {}

    public function index()
    {
        $this->authorize('viewAny', Holiday::class);
        $holidays = $this->holidayService->getAllOrderedByDate();
        return view('leave::holidays.index', compact('holidays'));
    }

    public function store(StoreHolidayRequest $request)
    {
        $this->authorize('create', Holiday::class);
        
        $dto = HolidayData::fromArray($request->validated(), saas_tenant('id'));
        $this->holidayService->createHoliday($dto);

        return back()->with('success', 'Holiday added successfully.');
    }

    public function destroy(Holiday $holiday)
    {
        $this->authorize('delete', $holiday);
        $this->holidayService->deleteHoliday($holiday);
        
        return back()->with('success', 'Holiday deleted successfully.');
    }
}
