<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'date_time'     => Carbon::parse($this->date_time)->toDayDateTimeString(),
            'amount'        => $this->amount,
            'advance'       => $this->advance,
            'due'           => $this->due,
            'name'          => $this->name,
            'email'         => $this->email,
            'phone'         => $this->phone,
            'message'       => $this->message,
            'category_name' =>$this->category?->name,
            'service_category_name'=>$this->category?->serviceCategory?->name,
            'created_at'    => $this->created_at->toDayDateTimeString(),
            'Transaction '  => AppointmentTransactionResource::collection($this->transactions)
        ];
    }
}
