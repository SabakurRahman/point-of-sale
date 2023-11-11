<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AllPostResource extends JsonResource
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
           'store_id'           => $this->store_id,
            'user_id'           => $this->user_id,
            'post_category_id'  => $this->post_category_id,
            'title'             =>$this->title,
             'slug'             =>$this->slug,
            'description'       =>$this->description,
            'cover_photo'       => !empty($this->cover_photo) ? url('uploads/post/' . $this->cover_photo) : url('default.webp'),
            'read_count'        =>$this->read_count,
            'status'            =>$this->status,
            'created_at'        =>$this->created_at,
            'updated_at'        =>$this->updated_at
        ];
    }
}
