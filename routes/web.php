<?php

use App\Http\Controllers\HolidayController;
use Illuminate\Support\Facades\Route;

Route::fallback(function () {
    return redirect('/');
});

Route::get('/', [HolidayController::class, 'index']);
Route::post('/holidays/check', [HolidayController::class, 'check']);
