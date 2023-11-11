<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemDateByTimeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->product['name'],
            'unit_price' => $this->unit_price,
            'quantity' => $this->quantity,
            'changed_price' => $this->changed_price,
            'created_at'   =>$this->created_at->toDayDateTimeString(),
            'order_id'   =>$this->order_id,
        ];
    }
}
