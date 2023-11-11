<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class OrderItem extends Model
{
    use HasFactory;

    public function order(){
        return $this->belongsTo(Order::class,'order_id');
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }


    public  function getOrderItemDetailsForDailySell(){
        $currentDate = Carbon::now()->format('Y-m-d');
        return $this->with('product')->whereDate('created_at', $currentDate)->paginate(10);
    }

    public function getOrderItemsDataByTime(Request $request, int $store_id)
    {
        $query = self::query()->orderByDesc('id')->select('id', 'unit_price', 'quantity', 'changed_price', 'product_id','created_at', 'total_price', 'order_id')->with('product:id,name')->whereHas('order', function ($q) use ($store_id) {
            $q->where('store_id', $store_id);
        });

        $order_query = Order::query()->select('id','discount', 'created_at', 'total_amount')->orderByDesc('id')->where('store_id' , $store_id);

        if ($request->input('type') == 'daily'){
            $query->whereDate('created_at', Carbon::now());
            $order_query->whereDate('created_at', Carbon::now()->endOfDay());
        }elseif ($request->input('type') == 'weekly'){
            $from = Carbon::now()->startOfWeek(CarbonInterface::SATURDAY);
            $to= Carbon::now()->endOfDay();
            $query->whereBetween('created_at', [$from, $to]);
            $order_query->whereBetween('created_at', [$from, $to]);
        }elseif ($request->input('type') == 'monthly'){
            $from = Carbon::now()->startOfMonth();
            $to= Carbon::now()->endOfDay();
            $query->whereBetween('created_at', [$from, $to]);
            $order_query->whereBetween('created_at', [$from, $to]);
        }elseif ($request->input('type') == 'date_range'){
            if (!empty($request->input('start_date')) && $request->input('end_date')){
                $from = Carbon::parse($request->input('start_date'))->startOfDay();
                $to=  Carbon::parse($request->input('end_date'))->endOfDay();
                $query->whereBetween('created_at', [$from, $to]);
                $order_query->whereBetween('created_at', [$from, $to]);
            }
        }

        $order_discount = $order_query->sum('discount');
        $total_amount =$order_query->sum('total_amount');
        $order_items = $query->paginate(10);
        $orders = $order_query->get();


        return [
            'order_discount'=>$order_discount,
            'order_items'=>$order_items,
            'sale_amount'=>$total_amount  - $order_discount,
            'total_amount' => $total_amount,
        ];
        //dd($data);
    }




}
