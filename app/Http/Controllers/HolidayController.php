<?php

namespace App\Http\Controllers;

use App\Http\Requests\HolidayRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class HolidayController extends Controller
{
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
            'isHoliday' => false,
            'message' => 'test',
        ]);
    }
}
