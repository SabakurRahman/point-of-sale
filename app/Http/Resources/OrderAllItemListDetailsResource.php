<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderAllItemListDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
           // 'order_id'      => $this->order_id,
           // 'product_id'    => $this->product_id,
            'product_name'    =>$this->product->name,
            'quantity'        =>$this->quantity,
            'unit_price'      => $this->unit_price,
            'total_price'    => $this->total_price,
            'changed_price'  =>$this->changed_price,
            //'created_at'    => $this->created_at->toDayDateTimeString(),
            //'updated_at'    => $this->updated_at->toDayDateTimeString(),
        ];
    }
}
