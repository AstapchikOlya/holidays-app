<?php

namespace App\Services;

use App\Models\Holiday;
use Carbon\Carbon;

class HolidayService
{
    public function __construct(private readonly StatService $statService) {}

    /**
     * @param string $date
     * @return string|null
     */
    public function checkHoliday(string $date): string|null
    {
        $dateStat = $this->statService->checkDateStat($date);

        if ($dateStat) {
            $holidayMsg = $this->statService->generateHolidayMsg($dateStat);
        } else {
            $holidayMsg = $this->checkAllHolidays($date);
        }

        return $holidayMsg;
    }

    /**
     * @param string $date
     * @return string|null
     */
    private function checkAllHolidays(string $date): string|null
    {
        $date = Carbon::parse($date);
        $holidays = Holiday::all();
        $foundHoliday = null;
        $isAdditionalDayOff = false;

        foreach ($holidays as $holiday) {
            if ($this->isHoliday($date, $holiday)) {
                $foundHoliday = $holiday;
                break;
            }
        }

        if (!$foundHoliday) {
          $foundHoliday = $this->checkAdditionalDayOff($date, $holidays);
          $isAdditionalDayOff = !!$foundHoliday;
        }

        return $foundHoliday
            ? $this->statService->addHolidayDate($date, $foundHoliday->id, $isAdditionalDayOff)
            : null;
    }

    /**
     * @param Carbon $date
     * @param Holiday $holiday
     * @return bool
     */
    private function isHoliday(Carbon $date, Holiday $holiday): bool
    {
        $month = $date->format('F');

        return $holiday->month === $month
            && ($this->isWithinPeriod($date, $holiday) || $this->isParticularWeekDay($date, $holiday));
    }

    /**
     * @param Carbon $date
     * @param Holiday $holiday
     * @return bool
     */
    private function isWithinPeriod(Carbon $date, Holiday $holiday): bool
    {
        $day = $date->day;

        return !is_null($holiday->day_from)
            && !is_null($holiday->day_to)
            && $day >= $holiday->day_from
            && $day <= $holiday->day_to;
    }

    /**
     * @param Carbon $date
     * @param Holiday $holiday
     * @return bool
     */
    private function isParticularWeekDay(Carbon $date, Holiday $holiday): bool
    {
        $weekDay = $date->format('l');
        $weekNumber = $date->weekOfMonth;

        return $holiday->week_day === $weekDay
            && ($holiday->week_number == $weekNumber || ($holiday->isLastWeek() && $this->isLastWeekDayOfMonth($date, $weekDay)));
    }

    /**
     * @param Carbon $date
     * @param string $weekDay
     * @return bool
     */
    private function isLastWeekDayOfMonth(Carbon $date, string $weekDay): bool {
        $lastDayOfMonth = $date->copy()->endOfMonth();

        while ($lastDayOfMonth->format('l') != $weekDay) {
            $lastDayOfMonth->subDay();
        }

        return $date->isSameDay($lastDayOfMonth);
    }

    /**
     * @param Carbon $date
     * @param \Illuminate\Support\Collection $holidays
     * @return Holiday|null
     */
    private function checkAdditionalDayOff(Carbon $date, \Illuminate\Support\Collection $holidays): Holiday|null
    {
        if ($date->isMonday()) {
            $prevSaturday = $date->copy()->subDays(2);
            $prevSunday = $date->copy()->subDay();

            foreach ($holidays as $holiday) {
                if ($this->isWeekendHoliday($prevSaturday, $holiday)
                    || $this->isWeekendHoliday($prevSunday, $holiday)
                ) {
                    return $holiday;
                }
            }
        }

        return null;
    }

    /**
     * @param Carbon $date
     * @param Holiday $holiday
     * @return bool
     */
    private function isWeekendHoliday(Carbon $date, Holiday $holiday): bool {
        $month = $date->format('F');

        return $holiday->month === $month
            && ($this->isParticularDay($date, $holiday) || $this->isParticularWeekDay($date, $holiday));
    }

    /**
     * @param Carbon $date
     * @param Holiday $holiday
     * @return bool
     */
    private function isParticularDay(Carbon $date, Holiday $holiday): bool
    {
        $day = $date->day;

        return !is_null($holiday->day_from)
            && $holiday->day_from === $holiday->day_to
            && $day === $holiday->day_from;
    }
}
