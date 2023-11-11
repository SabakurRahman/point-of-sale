<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerAllDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $statusText = $this->status === 1 ? 'Active' : 'Inactive';

        return [
            'id'                => $this->id,
            'address'           => $this->address,
            'name'              => $this->name,
            'phone'             => $this->phone,
            'status'            => $statusText,
           // 'membership_card_id'=>$this->membership_card_id,
           // 'store_id'          =>$this->store_id,
            'created_at'        => $this->created_at->toDayDateTimeString(),
            'updated_at'        => $this->updated_at->toDayDateTimeString(),
        ];
    }
}
