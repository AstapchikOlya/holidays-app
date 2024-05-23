<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 */
class Holiday extends Model
{
    use HasFactory;

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
}
