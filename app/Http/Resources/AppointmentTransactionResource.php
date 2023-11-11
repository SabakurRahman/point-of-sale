<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentTransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         =>$this->id,
            'amount'     =>$this->amount,
            'account_no' =>$this->account_no,
            'trx_no'     =>$this->trxId

        ];
    }
}
