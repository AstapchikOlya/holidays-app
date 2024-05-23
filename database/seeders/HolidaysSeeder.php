<?php

namespace Database\Seeders;

use App\Models\Holiday;
use Illuminate\Database\Seeder;

class HolidaysSeeder extends Seeder
{

    /**
     * @return void
     */
    public function run(): void
    {
        $holidays = [
            [
                'name' => 'New Year',
                'day_from' => 1,
                'day_to' => 1,
                'week_day' => null,
                'week_number' => null,
                'month' => 'January',
            ],
            [
                'name' => 'Orthodox Christmas',
                'day_from' => 7,
                'day_to' => 7,
                'week_day' => null,
                'week_number' => null,
                'month' => 'January',
            ],
            [
                'name' => 'May holiday week',
                'day_from' => 1,
                'day_to' => 7,
                'week_day' => null,
                'week_number' => null,
                'month' => 'May',
            ],
            [
                'name' => 'Martin Luther King Jr. Day',
                'day_from' => null,
                'day_to' => null,
                'week_day' => 'Monday',
                'week_number' => 3,
                'month' => 'January',
            ],
            [
                'name' => 'Mystery holiday',
                'day_from' => null,
                'day_to' => null,
                'week_day' => 'Monday',
                'week_number' => 'last',
                'month' => 'March',
            ],
            [
                'name' => 'Thanksgiving Day',
                'day_from' => null,
                'day_to' => null,
                'week_day' => 'Thursday',
                'week_number' => 4,
                'month' => 'November',
            ],
        ];

        foreach ($holidays as $holidayData) {
            Holiday::create($holidayData);
        }
    }
}
