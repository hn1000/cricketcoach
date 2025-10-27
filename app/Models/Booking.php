<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_id',
        'staff_id',
        'user_id',
        'company_id',
        'booking_date',
        'start_time',
        'end_time',
        'duration',
        'price',
        'status',
        'customer_name',
        'customer_email',
        'customer_phone',
        'notes',
        'confirmation_token',
        'cancellation_reason',
        'cancelled_at',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'duration' => 'integer',
        'price' => 'decimal:2',
        'cancelled_at' => 'datetime',
    ];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItem()
    {
        return $this->morphOne(OrderItem::class, 'orderable');
    }

    /**
     * Scope to get confirmed bookings only
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope to get bookings for a specific date
     */
    public function scopeForDate($query, $date)
    {
        return $query->where('booking_date', $date);
    }

    /**
     * Scope to get bookings between dates
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('booking_date', [$startDate, $endDate]);
    }

    /**
     * Check if this booking overlaps with a given time range on the same day
     */
    public function overlapsWithTimeRange($staffId, $date, $startTime, $endTime, $excludeId = null): bool
    {
        $query = self::where('staff_id', $staffId)
            ->where('booking_date', $date)
            ->whereIn('status', ['confirmed'])
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where(function ($q2) use ($startTime, $endTime) {
                    $q2->where('start_time', '<', $endTime)
                       ->where('end_time', '>', $startTime);
                });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Cancel this booking
     */
    public function cancel($reason = null)
    {
        $this->update([
            'status' => 'cancelled',
            'cancellation_reason' => $reason,
            'cancelled_at' => now(),
        ]);
    }
}
