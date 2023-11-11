<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostGetByIdResource extends JsonResource
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
            'store_id'            => $this->store_id,
            'user_id'             => $this->user_id,
            'post_category_id'    => $this->post_category_id,
            'title'               => $this->title,
            'title_bn'            => $this->title_bn,
            'slug'                => $this->slug,
            'slug_bn'             => $this->slug_bn,
            'status'              => $this->status,
            'description'         => $this->description,
            'description_bn'      => $this->description_bn,
            'cover_photo_display' => !empty($this->cover_photo) ? url('uploads/post/' . $this->cover_photo) : url('default.webp'),
            'read_count'          => $this->read_count,
            'created_at'          => $this->created_at,
            'updated_at'          => $this->updated_at,
            'meta_title'          => $this?->seo?->title,
            'meta_keyword'        => $this?->seo?->keyword,
            'og_image_display'    => !empty($this->seo?->og_image) ? url('uploads/seo/' . $this->seo?->og_image) : url('default.webp'),
            'meta_description'    => $this?->seo?->description,


        ];
    }
}
