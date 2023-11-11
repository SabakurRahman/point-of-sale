<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded =[];


    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function createTransactionDetails(array $request, Order $order)
    {

        return  self::query()->create($this->prepairTransactionData($request,$order));

    }

    private function prepairTransactionData(array $request, Order $order)
    {

        return [
            'payment_method_id' =>$request['payment_method'] ?? null,
            'order_id'          =>$order?->id,
            'user_id'           =>$order?->user_id,
            'customer_id'       => $order?->customer_id,
            'trxId'             =>$request['trxId'] ?? null,
            'amount'            =>$request['amount'] ?? $order->total_amount,
            'store_id'          =>$order->store_id,
            'account_no'        =>$request['account_no'] ?? null,
            'status'            =>1,
            'type'              =>1

        ];
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

}
