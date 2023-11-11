<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesManagerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
           // 'parent_id'     => $this->parent_id,
            'name'          => $this->name,
            'email'         => $this->email,
//            'address'       => $this->address,
//            'type'          => $this->type,
            'phone'         => $this->phone,
//            'store_id'      =>$this->store_id,
//            'status'        =>$this->status,
//            'created_at'    => $this->created_at->toDayDateTimeString(),
//            'updated_at'    => $this->updated_at->toDayDateTimeString(),

        ];
    }
}
