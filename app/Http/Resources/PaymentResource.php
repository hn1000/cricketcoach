<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'payment_gateway' => $this->payment_gateway,
            'gateway_transaction_id' => $this->gateway_transaction_id,
            'amount' => number_format($this->amount, 2),
            'amount_raw' => $this->amount,
            'currency' => $this->currency,
            'status' => $this->status,
            'status_formatted' => ucfirst($this->status),
            'payment_method' => $this->payment_method,
            'metadata' => $this->metadata,
            'processed_at' => $this->processed_at?->format('Y-m-d H:i:s'),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
