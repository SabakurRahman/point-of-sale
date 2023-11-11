<?php

namespace App\Http\Resources;

use App\Models\MembershipCard;
use App\Models\MembershipCardType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MembershipCardListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    final public function toArray(Request $request): array
    {
        return [
            'id'                   => $this->id,
            'card_no'              => $this->card_no,
            'membership_card_type' => $this->membership_card_type_id,
            'card_type'            => $this->membership_card_type?->card_type_name,
            'card_discount'        => $this->membership_card_type?->discount,
            'status'               => $this->status,
            'status_string'        => !empty($this->status) ? MembershipCard::STATUS_LIST[$this->status] : 'Inactive',
            'created_at'           => $this->created_at->toDayDateTimestring(),
            'created_by'           => $this->user?->name,
        ];
    }
}
