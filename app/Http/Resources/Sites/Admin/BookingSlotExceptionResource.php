<?php

namespace App\Http\Resources\Sites\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingSlotExceptionResource extends JsonResource
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
            'staff_id' => $this->staff_id,
            'exception_date' => $this->exception_date->format('Y-m-d'),
            'exception_date_formatted' => $this->exception_date->format('M d, Y'),
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'type' => $this->type,
            'type_label' => ucfirst(str_replace('_', ' ', $this->type)),
            'reason' => $this->reason,
            'notes' => $this->notes,
            'is_full_day' => $this->isFullDayBlock(),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
