<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingSlotException extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'staff_id',
        'exception_date',
        'start_time',
        'end_time',
        'type',
        'reason',
        'notes',
    ];

    protected $casts = [
        'exception_date' => 'date',
    ];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Scope to get exceptions for a specific date
     */
    public function scopeForDate($query, $date)
    {
        return $query->where('exception_date', $date);
    }

    /**
     * Scope to get exceptions within a date range
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('exception_date', [$startDate, $endDate]);
    }

    /**
     * Check if this exception blocks the entire day
     */
    public function isFullDayBlock(): bool
    {
        return $this->type === 'unavailable' &&
               (is_null($this->start_time) || is_null($this->end_time));
    }

    /**
     * Check if a specific time range is blocked by this exception
     */
    public function blocksTimeRange($startTime, $endTime): bool
    {
        if ($this->isFullDayBlock()) {
            return true;
        }

        if (is_null($this->start_time) || is_null($this->end_time)) {
            return false;
        }

        // Check for time overlap
        return ($startTime < $this->end_time && $endTime > $this->start_time);
    }
}
