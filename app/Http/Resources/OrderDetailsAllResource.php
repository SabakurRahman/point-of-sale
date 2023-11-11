<?php

namespace App\Http\Resources;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailsAllResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
//            'store_id'         => $this->store_id,
            'order_date'         => $this->created_at->toDayDateTimeString(),
//            'user_id'          => $this->user_id,
//            'customer_name'    => $this->customer_name,
//            'customer_phone'   => $this->customer_phone,
//            'customer_email'   => $this->customer_email,
//            'customer_address' => $this->customer_address,
            'total_amount'     => $this->total_amount,
            'net_amount'     => $this->total_amount - $this->discount,
//            'payment_method'   => $this->payment_method,
            'created_at'       => $this->created_at->toDayDateTimeString(),
            'updated_at'       => $this->updated_at->toDayDateTimeString(),
//            'trx_id'           => $this->trx_id,
            'discount'         =>$this->discount,
            'given_amount'     =>$this->given_amount,
            'changed_amount'   =>$this->changed_amount,
//            'customer_id'      =>$this->customer_id,
            'membership_card_id'=>$this->membership_card_id,
            'customer'         => new CustomerAllDetailsResource($this->customer),
            'orderitems'       => OrderAllItemListDetailsResource::collection($this->orderitems),
            'transactions'     =>TransactionListResource::collection($this->transactions),
            'sales_manager'    => new SalesManagerResource($this->salesManager),
            'total_order_list'            =>OrderResouece::collection($this?->customer?->order)

        ];
    }
}
