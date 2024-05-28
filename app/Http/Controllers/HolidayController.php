<?php

namespace App\Http\Controllers;

use App\Http\Requests\HolidayRequest;
use App\Services\HolidayService;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class HolidayController extends Controller
{
    /**
     * @param HolidayService $holidayService
     */
    public function __construct(private readonly HolidayService $holidayService) {}

    /**
     * @return View
     */
    public function index(): View
    {
        return view('holidays');
    }

    /**
     * @param HolidayRequest $request
     * @return JsonResponse
     */
    public function check(HolidayRequest $request): JsonResponse
    {
        $date = Carbon::createFromFormat('Y-m-d', $request->input('date'));
        $holidayMsg = $this->holidayService->checkHoliday($date);

        return response()->json([
            'isHoliday' => !!$holidayMsg,
            'holidayMsg' => $holidayMsg ?? "It's an ordinary date",
        ]);
    }
}
