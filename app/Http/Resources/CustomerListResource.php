<?php

namespace App\Http\Resources;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                   => $this->id,
            'address'              => $this->address,
            'status'               => !empty($this->status) ? Customer::STATUS_LIST[$this->status] : '',
            'name'                 => $this->name,
            'phone'                => $this->phone,
            'membership_card'      => $this->membership_card?->card_no,
            'membership_card_type' => $this->membership_card?->membership_card_type?->card_type_name,
        ];
    }
}
