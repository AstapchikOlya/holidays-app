<?php

namespace App\Services;

use App\Models\Stat;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatService
{

    public const HOLIDAY_MSG = "It's %s on that date!";
    public const ADDITIONAL_DAY_OFF_MSG = "It's an additional day off today 'cause this weekend was %s.";
    public const STATS_MSG = "This date has been checked %d time(s).";

    /**
     * @param Stat $dateStat
     * @return string
     */
    public function generateHolidayMsg(Stat $dateStat): string
    {
        $statMsg = sprintf(self::STATS_MSG, $dateStat->count);
        $holidayMsg = $dateStat->is_additional_day_off
            ? sprintf(self::ADDITIONAL_DAY_OFF_MSG, $dateStat->holiday->name)
            : sprintf(self::HOLIDAY_MSG, $dateStat->holiday->name);

        return "$holidayMsg $statMsg";
    }

    /**
     * @param string $date
     * @return Stat|null
     */
    public function checkDateStat(string $date): Stat|null
    {
        DB::beginTransaction();
        try {
            $dateStat = Stat::where('date', $date)->lockForUpdate()->first();

            if ($dateStat) {
                $this->increaseDateCounter($dateStat);
            }

            DB::commit();
        } catch (\Exception $e) {
            $dateStat = null;
            DB::rollBack();
        }

        return $dateStat;
    }

    /**
     * @param Carbon $date
     * @param int $holidayId
     * @param bool $isAdditionalDayOff
     * @return string
     */
    public function addHolidayDate(Carbon $date, int $holidayId, bool $isAdditionalDayOff = false): string
    {
        $stat = Stat::create([
            'holiday_id'            => $holidayId,
            'date'                  => $date,
            'is_additional_day_off' => $isAdditionalDayOff,
            'count'                 => 1,
        ]);

        return $this->generateHolidayMsg($stat);
    }

    /**
     * @param Stat $dateState
     * @return void
     */
    private function increaseDateCounter(Stat $dateState): void
    {
        $dateState->count++;
        $dateState->save();
    }
}
