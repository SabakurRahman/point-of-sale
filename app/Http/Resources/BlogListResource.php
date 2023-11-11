<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class BlogListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                 =>$this->id,
            'user_name'          =>$this?->user?->name,
            'post_category_name' =>$this?->postCategory?->name,
            'post_category_slug' =>$this?->postCategory?->slug,
            'cover_photo'        => !empty($this->cover_photo) ? url('uploads/post/' . $this->cover_photo) : url('default.webp'),
            'read_count'          =>$this->read_count,
            'title'              => App::getLocale() == 'en' ? $this->title : $this->title,
            'slug'               => App::getLocale() == 'en' ? $this->slug  : $this->slug,
            'description'        => substr( strip_tags(App::getLocale() == 'en' ? $this->description : $this->description_bn),0,200),
            'created_at'         => $this->created_at->toDayDateTimeString(),
            'updated_at'         => $this->updated_at->toDayDateTimeString()

        ];
    }
}
