<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property int $day_from
 * @property int $day_to
 * @property string $week_day
 * @property int|string $week_number
 * @property string $month
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Stat $stat
 */
class Holiday extends Model
{
    use HasFactory;

    /**
     *
     */
    public const LAST_WEEK = 'last';

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'day_from',
        'day_to',
        'week_day',
        'week_number',
        'month',
    ];

    /**
     * @return HasMany
     */
    public function stats(): HasMany
    {
        return $this->hasMany(Stat::class);
    }

    /**
     * @return bool
     */
    public function isLastWeek(): bool
    {
        return $this->week_number === self::LAST_WEEK;
    }
}
