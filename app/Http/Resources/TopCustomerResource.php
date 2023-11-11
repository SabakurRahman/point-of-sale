<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TopCustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name'               => $this->name,
            'total_order_amount' => $this->order->sum('total_amount'),
            'total_order_count'  => $this->order_count,
            'phone'              => $this->phone,
            'address'            => $this->address,
            'discount'           => $this->order->sum('discount')
        ];
    }
}
