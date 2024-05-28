<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $condition
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Stat $stat
 */
class Holiday extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'condition',
    ];

    /**
     *
     */
    const DAY_ENDINGS = [
        'st', 'nd', 'rd', 'th'
    ];

    /**
     *
     */
    const DAYS_OF_WEEK = [
        'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'
    ];

    /**
     * @return HasMany
     */
    public function stats(): HasMany
    {
        return $this->hasMany(Stat::class);
    }

    /**
     * @return string
     */
    public static function getParticularDatePattern(): string
    {
        $dayEndings = implode('|', self::DAY_ENDINGS);
        return "/^(\\d{1,2})(?:$dayEndings) of (\\w+)$/";
    }

    /**
     * @return string
     */
    public static function getDatePeriodPattern(): string
    {
        $dayEndings = implode('|', self::DAY_ENDINGS);
        return "/^From (\\d{1,2})(?:$dayEndings) of (\\w+) till (\\d{1,2})(?:$dayEndings) of (\\w+)$/";
    }

    /**
     * @return string
     */
    public static function getParticularWeekDayPattern(): string
    {
        $dayEndings = implode('|', self::DAY_ENDINGS);
        $daysOfWeek = implode('|', self::DAYS_OF_WEEK);
        return "/^($daysOfWeek) of the (\\d+)(?:$dayEndings) week of (\\w+)$/";
    }

    /**
     * @return string
     */
    public static function getLastWeekDayPattern(): string
    {
        $daysOfWeek = implode('|', self::DAYS_OF_WEEK);
        return "/^($daysOfWeek) of the last week of (\\w+)$/";
    }
}
