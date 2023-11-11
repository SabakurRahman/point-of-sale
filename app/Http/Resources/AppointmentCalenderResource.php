<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentCalenderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             =>$this->id,
            'title'          =>$this->product->name,
            'date'           =>Carbon::parse($this->date_time)->format('Y-m-d'),
            'name'           =>$this->name,
            'email'          =>$this->email,
            'phone'          =>$this->phone,
            'amount'         =>$this->amount,
            'advance'        =>$this->advance,
            'due'            =>$this->due,
            'created_at'     => $this->created_at,
            'updated_at'     =>$this->updated_at

        ];
    }
}
