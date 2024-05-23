<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
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
    ];

    /**
     * @return BelongsTo
     */
    public function holiday(): BelongsTo
    {
        return $this->belongsTo(Holiday::class);
    }
}
