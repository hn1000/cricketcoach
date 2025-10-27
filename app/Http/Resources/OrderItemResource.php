<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'orderable_type' => $this->orderable_type,
            'orderable_id' => $this->orderable_id,
            'quantity' => $this->quantity,
            'unit_price' => number_format($this->unit_price, 2),
            'total_price' => number_format($this->total_price, 2),
            'metadata' => $this->metadata,

            // Orderable details
            'orderable' => $this->when($this->relationLoaded('orderable'), function () {
                if ($this->orderable_type === 'App\\Models\\Booking' && $this->orderable) {
                    return [
                        'type' => 'booking',
                        'booking_date' => $this->orderable->booking_date->format('Y-m-d'),
                        'start_time' => substr($this->orderable->start_time, 0, 5),
                        'end_time' => substr($this->orderable->end_time, 0, 5),
                        'staff_name' => $this->orderable->staff->first_name . ' ' . $this->orderable->staff->last_name,
                        'status' => $this->orderable->status,
                    ];
                }
                return null;
            }),
        ];
    }
}
