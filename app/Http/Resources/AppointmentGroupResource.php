<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentGroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'service_category' => $this->service_category?->name,
            'category' => $this->category?->name,
            'product' => $this->product?->name,
            'amount' => $this->amount,
            'advance'   => 0,
            'due'       =>  $this->amount-0,
            'date_time' => Carbon::parse($this->date_time)->toDayDateTimeString(),

        ];
    }
}
