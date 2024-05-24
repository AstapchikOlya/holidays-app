<?php

namespace App\Http\Controllers;

use App\Http\Requests\HolidayRequest;
use App\Services\HolidayService;
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
        $date = $request->input('date');

        return response()->json([
            'holidayMsg' => $this->holidayService->checkHoliday($date),
        ]);
    }
}
