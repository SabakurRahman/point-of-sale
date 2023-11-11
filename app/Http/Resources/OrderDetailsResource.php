<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $payment_methods = $this->transactions->pluck('payment_method_id')->toArray();
        $payment_method_string = '';
        foreach ($payment_methods as $payment_method_id){
            $payment_method_string.= Order::PAYMENT_METHOD_LIST[$payment_method_id] . ' ' ?? null . ' ';
        }
        return [
            'id'                  => $this->id,
            'sub_total'           => $this->total_amount,
            'discount'            => $this->discount,
            'payable_amount'      => $this->total_amount - $this->discount,
            'payment_method'      => $payment_method_string,
            'given_amount'        => $this->given_amount,
            'changed_amount'      => $this->changed_amount,
            'membership_card'     => $this->membership_card?->card_no,
            'customer'            => new CustomerDetailsResource($this->customer),
            'order_items'         => OrderItemListResource::collection($this->orderitems),
            'order_date_time'     => $this->created_at->toDayDateTimeString(),
        ];
    }
}
