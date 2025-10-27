<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'order_number' => $this->order_number,
            'order_type' => $this->order_type,
            'status' => $this->status,
            'status_formatted' => ucfirst($this->status),

            // Customer info
            'customer_name' => $this->customer_name,
            'customer_email' => $this->customer_email,
            'customer_phone' => $this->customer_phone,

            // Amounts
            'subtotal' => number_format($this->subtotal, 2),
            'tax' => number_format($this->tax, 2),
            'total' => number_format($this->total, 2),
            'currency' => $this->currency,

            // Raw values for calculations
            'subtotal_raw' => $this->subtotal,
            'tax_raw' => $this->tax,
            'total_raw' => $this->total,

            // Company
            'company' => $this->whenLoaded('company', [
                'id' => $this->company->id,
                'name' => $this->company->name,
            ]),

            // Items
            'items' => OrderItemResource::collection($this->whenLoaded('items')),

            // Payment
            'payment' => $this->whenLoaded('payment', function () {
                return $this->payment ? new PaymentResource($this->payment) : null;
            }),

            // Timestamps
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'created_at_formatted' => $this->created_at->format('M j, Y g:i A'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
