<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
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
            'booking_date' => $this->booking_date->format('Y-m-d'),
            'booking_date_formatted' => $this->booking_date->format('l, F j, Y'),
            'start_time' => substr($this->start_time, 0, 5), // HH:MM
            'end_time' => substr($this->end_time, 0, 5),
            'duration' => $this->duration,
            'duration_formatted' => $this->duration . ' minutes',
            'price' => number_format($this->price, 2),
            'price_raw' => $this->price,
            'status' => $this->status,
            'status_formatted' => ucwords(str_replace('_', ' ', $this->status)),

            // Customer information
            'customer_name' => $this->customer_name,
            'customer_email' => $this->customer_email,
            'customer_phone' => $this->customer_phone,
            'notes' => $this->notes,

            // Related data
            'company' => [
                'id' => $this->company->id,
                'name' => $this->company->name,
            ],
            'staff' => [
                'id' => $this->staff->id,
                'name' => $this->staff->first_name . ' ' . $this->staff->last_name,
                'first_name' => $this->staff->first_name,
                'last_name' => $this->staff->last_name,
                'position' => $this->staff->position,
            ],

            // Order information (if loaded)
            'order' => $this->whenLoaded('order', function () {
                return [
                    'id' => $this->order->id,
                    'order_number' => $this->order->order_number,
                    'status' => $this->order->status,
                    'total' => number_format($this->order->total, 2),
                ];
            }),

            // Payment information (if order and payment loaded)
            'payment_status' => $this->when($this->relationLoaded('order'), function () {
                if ($this->order && $this->order->relationLoaded('payment') && $this->order->payment) {
                    return $this->order->payment->status;
                }
                return $this->order ? $this->order->status : null;
            }),

            // Cancellation info
            'cancelled_at' => $this->cancelled_at?->format('Y-m-d H:i:s'),
            'cancellation_reason' => $this->cancellation_reason,

            // Timestamps
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
