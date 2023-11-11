<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceCategoryListResource extends JsonResource
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
            'name' => $this->name,
            'duration' => $this->duration,
            'status' => $this->status==1?"Active":"Inactive",
            'categories' => CategoryResource::collection($this->categories)
        ];
    }
}
