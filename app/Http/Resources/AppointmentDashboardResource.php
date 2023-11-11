<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentDashboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'service_category_name' => $this->category->serviceCategory->name,
            'category_name'         => $this->category->name,
            'product_name'          =>$this->product->name,
            'date_time'             =>Carbon::parse($this->date_time)->toDayDateTimeString(),
            'amount'                =>$this->amount,
            'name'                  =>$this->name,
            'email'                 =>$this->email,
            'phone'                 =>$this->phone,
            'message'               =>$this->message,
            'created_at'            =>$this->created_at->toDayDateTimeString()


        ];
    }
}
