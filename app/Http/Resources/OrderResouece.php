<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResouece extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                  => $this->id,
            'sub_total'           => $this->total_amount,
            'discount'            => $this->discount,
            'payable_amount'      => $this->total_amount - $this->discount,
            'payment_method'      => !empty($this->payment_method) ? Order::PAYMENT_METHOD_LIST[$this->payment_method]: 'split',
            'given_amount'        => $this->given_amount,
            'changed_amount'      => $this->changed_amount,
            'membership_card'     => $this->membership_card?->card_no,

        ];
    }
}
