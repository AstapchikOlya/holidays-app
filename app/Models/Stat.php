<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $holiday_id
 * @property Carbon $date
 * @property int $count
 * @property bool $is_additional_day_off
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Holiday $holiday
 */
class Stat extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'holiday_id',
        'date',
        'count',
        'is_additional_day_off',
    ];

    /**
     * @return BelongsTo
     */
    public function holiday(): BelongsTo
    {
        return $this->belongsTo(Holiday::class);
    }
}
