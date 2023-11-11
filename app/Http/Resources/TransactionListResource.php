<?php

namespace App\Http\Resources;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $statusText = $this->status === 1 ? 'success' : 'failed';

            return [
                'id'                => $this->id,
               // 'payment_method_id' => $this->payment_method_id,
                'payment_method_name' => Order:: PAYMENT_METHOD_LIST[$this->payment_method_id],
             //   'order_id'          => $this->order_id,
               // 'user_id'           => $this->user_id,
             //   'customer_id'       => $this->customer_id,
                'trxId'             => $this->trxId,
                'amount'            => $this->amount,
                'status'            =>  $statusText,
                'created_at'        => $this->created_at->toDayDateTimeString(),
                'updated_at'        => $this->updated_at->toDayDateTimeString(),

            ];

        }

}

