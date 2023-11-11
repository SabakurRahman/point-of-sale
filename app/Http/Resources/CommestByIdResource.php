<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommestByIdResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'post_id'    => $this->post_id,
            'comment_id' => $this->comment_id,
            'comment'  => $this->comment,
            'is_approve'=> $this->is_active==1?"Active":"inactive"
        ];
    }
}
