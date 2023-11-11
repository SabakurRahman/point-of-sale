<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceByCategoryResource extends JsonResource
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
            'image' => !empty($this->image) ? url('uploads/product/' . $this->image) : url('default.webp'),
            'name' => $this->name,
            'price' => $this->price,
            'product_features'=>ProductFeatureResource::collection($this->product_features),
        ];
    }
}
