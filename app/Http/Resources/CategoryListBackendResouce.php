<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryListBackendResouce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'name'             => $this->name,
            'status'           => $this->status,
            'service_category' => $this?->serviceCategory?->name,
            'created_by'       => $this?->created_by?->name,
            'created_at'       => $this->created_at->toDayDateTimeString(),
            'updated_at'       => $this->created_at != $this->updated_at ? $this->updated_at->toDayDateTimeString() : 'Not updated',
        ];
    }
}
