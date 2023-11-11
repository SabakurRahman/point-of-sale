<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class Order extends Model
{
    use HasFactory;

    public const PAYMENT_METHOD_CASH = 1;
    public const PAYMENT_METHOD_BKASH = 2;
    public const PAYMENT_METHOD_NAGAD = 3;
    public const PAYMENT_METHOD_CARD = 4;

    public const PAYMENT_METHOD_SSLCOMMERZ = 5;

    public const PAYMENT_METHOD_LIST = [
        self::PAYMENT_METHOD_CASH => 'Cash on Hand',
        self::PAYMENT_METHOD_BKASH => 'Bkash',
        self::PAYMENT_METHOD_NAGAD => 'Nagad',
        self::PAYMENT_METHOD_CARD => 'Card',
        self::PAYMENT_METHOD_SSLCOMMERZ=>'Sslcommerz'
    ];

    public function stores()
    {
        return $this->belongsToMany(Store::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function orderitems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function salesManager()
    {
        return $this->belongsTo(User::class, 'user_id');

    }


    public function getOrderAllDetails($order_id)
    {
        return $order = Order::with('customer','customer.order', 'orderitems', 'transactions', 'salesManager', 'orderitems.product')->find($order_id);


    }

    /**
     * @param $store_id
     * @param $order_id
     * @return bool|mixed|null
     */

    public function deleteOrder($store_id, $order_id)
    {

        $order = self::with('orderitems', 'orderitems.product', 'transactions')->find($order_id);
        Log::info('ORDER_DELETE_DATA', ['data' => $order, 'user'=>Auth::user()]);
        if ($order) {
            foreach ($order->transactions as $transaction) {
                $transaction->delete();
            }
            foreach ($order->orderitems as $order_item) {
                $product = $order_item->product;
                $product->stock += $order_item->quantity;
                $product->save();
                $order_item->delete();
            }
            return $order->delete();
        }
        return false;
    }

    public function paymentMethod()
    {
        return self::PAYMENT_METHOD_LIST;
    }


}
