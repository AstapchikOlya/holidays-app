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
                'condition' => '1st of January',
            ],
            [
                'name' => 'Orthodox Christmas',
                'condition' => '7th of January',
            ],
            [
                'name' => 'May holiday week',
                'condition' => 'From 1st of May till 7th of May',
            ],
            [
                'name' => 'Martin Luther King Jr. Day',
                'condition' => 'Monday of the 3rd week of January',
            ],
            [
                'name' => 'Mystery holiday',
                'condition' => 'Monday of the last week of March',
            ],
            [
                'name' => 'Thanksgiving Day',
                'condition' => 'Thursday of the 4th week of November',
            ],
        ];

        foreach ($holidays as $holidayData) {
            Holiday::create($holidayData);
        }
    }
}
