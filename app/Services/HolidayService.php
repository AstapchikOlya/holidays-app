<?php

namespace App\Services;

use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class HolidayService
{
    public function __construct(private readonly StatService $statService) {}

    /**
     * @param Carbon $date
     * @return string|null
     */
    public function checkHoliday(Carbon $date): string|null
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
     * @param Carbon $date
     * @return string|null
     */
    private function checkAllHolidays(Carbon $date): string|null
    {
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

        $holidayStat = $foundHoliday
            ? $this->statService->addHolidayDate($date, $foundHoliday->id, $isAdditionalDayOff)
            : null;

        return $holidayStat
            ? $this->statService->generateHolidayMsg($holidayStat)
            : null;
    }

    /**
     * @param Carbon $date
     * @param Holiday $holiday
     * @return bool
     */
    private function isHoliday(Carbon $date, Holiday $holiday): bool
    {
        $condition = $holiday->condition;

        return $this->isParticularDate($date, $condition)
            || $this->isWithinPeriod($date, $condition)
            || $this->isParticularWeekDay($date, $condition)
            || $this->isLastWeekDay($date, $condition);
    }

    /**
     * @param Carbon $date
     * @param string $holidayCondition
     * @return bool
     */
    private function isParticularDate(Carbon $date, string $holidayCondition): bool
    {
        if (preg_match(Holiday::getParticularDatePattern(), $holidayCondition, $matches)) {
            [, $day, $month] = $matches;

            return $date->day == $day
                && $date->englishMonth === $month;
        }

        return false;
    }

    /**
     * @param Carbon $date
     * @param string $holidayCondition
     * @return bool
     */
    private function isWithinPeriod(Carbon $date, string $holidayCondition): bool
    {
        if (preg_match(Holiday::getDatePeriodPattern(), $holidayCondition, $matches)) {
            $year = $date->year;
            [, $dayFrom, $monthFrom, $dayTo, $monthTo] = $matches;

            $startDate = Carbon::create($year, Carbon::parse($monthFrom)->month, $dayFrom);
            $endDate = Carbon::create($year, Carbon::parse($monthTo)->month, $dayTo)->endOfDay();

            return $date->betweenIncluded($startDate, $endDate);
        }

        return false;
    }

    /**
     * @param Carbon $date
     * @param string $holidayCondition
     * @return bool
     */
    private function isParticularWeekDay(Carbon $date, string $holidayCondition): bool
    {
        if (preg_match(Holiday::getParticularWeekDayPattern(), $holidayCondition, $matches)) {
            [, $weekDay, $weekNumber, $month] = $matches;

            if ($date->englishMonth !== $month) {
                return false;
            }

            $firstDayOfMonth = $date->copy()->firstOfMonth();
            $targetDate = $firstDayOfMonth->copy();

            if ($targetDate->englishDayOfWeek !== $weekDay) {
                $targetDate->next($weekDay);
            }

            for ($i = 1; $i < $weekNumber; $i++) {
                $targetDate->addWeek();
            }

            return $date->isSameDay($targetDate);
        }

        return false;
    }

    /**
     * @param Carbon $date
     * @param string $holidayCondition
     * @return bool
     */
    private function isLastWeekDay(Carbon $date, string $holidayCondition): bool
    {
        if (preg_match(Holiday::getLastWeekDayPattern(), $holidayCondition, $matches)) {
            [, $weekDay, $month] = $matches;

            if ($date->englishMonth !== $month) {
                return false;
            }

            $lastDayOfMonth = $date->copy()->endOfMonth();
            $targetDate = $lastDayOfMonth->copy();

            if ($targetDate->englishDayOfWeek !== $weekDay) {
                $targetDate->previous($weekDay);
            }

            return $date->isSameDay($targetDate);
        }

        return false;
    }

    /**
     * @param Carbon $date
     * @param Collection $holidays
     * @return Holiday|null
     */
    private function checkAdditionalDayOff(Carbon $date, Collection $holidays): Holiday|null
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
        $condition = $holiday->condition;

        return $this->isParticularDate($date, $condition)
            || $this->isParticularWeekDay($date, $condition)
            || $this->isLastWeekDay($date, $condition);
    }

}
