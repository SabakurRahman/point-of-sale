<?php

namespace App\Http\Resources;

use App\Models\MembershipCardType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MembershipCardTypeListResource extends JsonResource
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
            'card_type_name' => $this->card_type_name,
            'discount' => $this->discount,
            'status' => $this->status,
            'status_string' => !empty($this->status) ? MembershipCardType::STATUS_LIST[$this->status] : 'Inactive' ,
            'created_at' => $this->created_at->toDayDateTimestring(),
            'created_by' => $this->user?->name,
        ];
    }
}
