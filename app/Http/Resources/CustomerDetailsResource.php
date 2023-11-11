<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerDetailsResource extends JsonResource
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
            'address' => $this->address,
            'name' => $this->name,
            'phone' => $this->phone,
            'card_no' => $this?->membership_card?->card_no,
            'card_id' => $this?->membership_card?->id,
            'discount' => $this?->membership_card?->membership_card_type?->discount,
            'order_count'=>$this?->order?->count(),
            'total_amount'=>$this?->order?->sum('total_amount')
        ];
    }
}
