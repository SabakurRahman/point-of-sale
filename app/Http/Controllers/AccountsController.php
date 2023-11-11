<?php

namespace App\Http\Controllers;

use App\Http\Resources\DailySaleListResource;
use App\Http\Resources\OrderDetailsResource;
use App\Http\Resources\OrderItemDateByTimeResource;
use App\Models\Accounts;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AccountsController extends Controller
{



    public function getprice(Request $request, $store_id) {
        $currentDate = Carbon::now()->format('Y-m-d');
        $account = Accounts::where('store_id', $store_id)->where('date', $currentDate)->first();

        if ($account) {
            $openingPrice = $account->opening_price;
            return response()->json([
                'success' => true,
                'message' => 'Today Opening Price',
                'data' => [$openingPrice]
            ], 200);
        } else {
            $openingPrice = 0;
            return response()->json([
                'success' => true,
                'message' => 'No opening price found for the specified store and date.',
                'data' => [$openingPrice]
            ], 200);
        }
    }


        public function register(Request $request,$store_id)
    {
        $validator = Validator::make($request->all(), [
            'opening_price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $currentDate = Carbon::now()->format('Y-m-d');

        $account = Accounts::where('date', $currentDate)->where('store_id', $store_id)->first();

        if ($account) {
            // Update the existing opening price
            $account->opening_price = $request->opening_price;
            $account->save();
        } else {
            // Create a new account record
            $account = new Accounts();
            $account->date = $currentDate;
            $account->opening_price = $request->opening_price;
            $account->store_id = $store_id;
            $account->save();
        }

        if ($account) {
            return response()->json([
                'success' => true,
                'message' => 'Opening Price Successfully registered',
                'data' => [$account]
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to register Opening Price',
                'data' => ''
            ], 404);
        }
    }



    public function dailysale(Request $request , $store_id)
{

    //$store_id=$request->store_id;
    // Get the current date
    $currentDate = date('Y-m-d');

    // Get the opening price for the current date
    $openingPriceRecord = Accounts::where('store_id', $store_id)
    ->whereDate('date', $currentDate)
    ->whereNotNull('opening_price')
    ->select('opening_price')
    ->first();


    if ($openingPriceRecord) {
        $openingPrice = $openingPriceRecord->opening_price;
    }
    // Get the total amount from the accounts table for the current date
    $dailySale = Order::query()->where('store_id', $store_id)->whereDate('created_at', $currentDate)->get();

    // Calculate the final result by adding the opening price and the daily sale
    $totalSale = $dailySale->sum('total_amount') - $dailySale->sum('discount');

    return response()->json([
        'success' => true,
        'message' => 'Daily Sale',
        'data' => [
            'date' => $currentDate,
            'opening_price' => $openingPrice ?? 0,
            'daily_sale' => $totalSale ?? 0,
            'total_amount' => $totalSale ?? 0,
        ]
    ], 200);
}


 public function saleList(Request $request, $id){

     $order_items_and_discount =  (new OrderItem())->getOrderItemsDataByTime( $request, $id);

     $orderItemsDataByTime = $order_items_and_discount['order_items'];

     $currentDate = Carbon::now()->format('Y-m-d');


     // Return the formatted response
     return response()->json([
         'success' => true,
         'message' => 'Get Order Items DataByTime',
         'data' => OrderItemDateByTimeResource::collection($orderItemsDataByTime),
         'date' => $currentDate,
         'opening_price' => $openingPrice ?? 0,
         'sale_amount' => $order_items_and_discount['sale_amount'] ?? 0,
         'total_amount' => $order_items_and_discount['total_amount'] ?? 0,
         'total_discount' => $order_items_and_discount['order_discount'] ?? 0,
         'items_total_changed' => $orderItemsDataByTime->sum('changed_price'),
         'items_total_price' => $orderItemsDataByTime->sum('total_price'),
         'meta' => [
             'total' =>  $orderItemsDataByTime->total(),
             'per_page' =>  $orderItemsDataByTime->perPage(),
             'total_pages' =>  $orderItemsDataByTime->lastPage(),
             'current_page' =>  $orderItemsDataByTime->currentPage(),
             'last_page' => $orderItemsDataByTime->lastPage(),
             'from' =>  $orderItemsDataByTime->firstItem(),
             'to' =>  $orderItemsDataByTime->lastItem(),
         ],
         'links' => [
             'first_page_url' =>  $orderItemsDataByTime->url(1),
             'last_page_url' =>  $orderItemsDataByTime->url($orderItemsDataByTime->lastPage()),
             'next_page_url' =>  $orderItemsDataByTime->nextPageUrl(),
             'prev_page_url' =>  $orderItemsDataByTime->previousPageUrl(),
         ],
     ], 200);



  }









        public function daily_sale_list(Request $request, $store_id)
{
    $store_id = $request->store_id;
    // Get the start and end dates of the current week
    $currentDate = Carbon::now()->format('Y-m-d');

    // Retrieve the sale list for the current day
//            $dailySales = Accounts::where('store_id', $store_id)
//                ->whereDate('created_at', $currentDate)
//                ->select('name', 'quantity', 'price')
//                ->whereNotNull('name');

    // $dailySales = (new OrderItem())->getOrderItemDetailsForDailySell();
    $dailySales = (new OrderItem())->getOrderItemDetailsForDailySell();

    // Format the data using the resource
    $formattedData = DailySaleListResource::collection($dailySales);
    // dd($dailySales);
    // $dailySales = $dailySales->paginate(10);
    // dd( $dailySales);

    if ($dailySales->isEmpty()) {
        return response()->json([
            'data' => [],
            'meta' => [
                'current_page' => 0,
                'last_page' => 0,
                'per_page' => 10,
                'total' => 0,
            ],
            'links' => [
                'first_page_url' => null,
                'last_page_url' => null,
                'next_page_url' => null,
                'prev_page_url' => null,
            ],
        ]);
    }


//            $openingPrice = Accounts::where('store_id', $store_id)
//            ->where('created_at', $currentDate)
//            ->whereNotNull('opening_price')
//            ->sum('opening_price');
//
//            if ($openingPrice) {
//                $openingPrice = $openingPrice;
//            } else {
//                $openingPrice = 0;
//            }
//            $dailySale = Accounts::where('store_id', $store_id)->whereDate('created_at', $currentDate)->sum('price');
//            $totalSale = $openingPrice ?? 0 + $dailySale;

    // Return the formatted response
    return response()->json([
        'success' => true,
        'message' => 'Daily Sale List',
        'data' => $formattedData,
        'date' => $currentDate,
        'opening_price' => $openingPrice ?? 0,
        'daily_sale' => $dailySale ?? 0,
        'total_amount' => $totalSale ?? 0,
        'meta' => [
            'total' => $dailySales->total(),
            'per_page' => $dailySales->perPage(),
            'total_pages' => $dailySales->lastPage(),
            'current_page' => $dailySales->currentPage(),
            'last_page' => $dailySales->lastPage(),
            'from' => $dailySales->firstItem(),
            'to' => $dailySales->lastItem(),
        ],
        'links' => [
            'first_page_url' => $dailySales->url(1),
            'last_page_url' => $dailySales->url($dailySales->lastPage()),
            'next_page_url' => $dailySales->nextPageUrl(),
            'prev_page_url' => $dailySales->previousPageUrl(),
        ],
    ], 200);
}

        public function weekly_sale_list(Request $request, $store_id)
        {
            $store_id=$request->store_id;
            // Get the start and end dates of the current week
            $startOfWeek = Carbon::now()->startOfWeek(Carbon::FRIDAY)->format('Y-m-d');
            $endOfWeek = Carbon::now()->endOfWeek(Carbon::THURSDAY)->format('Y-m-d');

            // Retrieve the sale list for the current week
            $weeklySales = Accounts::where('store_id',$store_id)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->select('name', 'quantity', 'price')
            ->whereNotNull('name');

            $weeklySales = $weeklySales->paginate(10);

            if ($weeklySales->isEmpty()) {
                return response()->json([
                    'data' => [],
                    'meta' => [
                        'current_page' => 0,
                        'last_page' => 0,
                        'per_page' => 10,
                        'total' => 0,
                    ],
                    'links' => [
                        'first_page_url' => null,
                        'last_page_url' => null,
                        'next_page_url' => null,
                        'prev_page_url' => null,
                    ],
                ]);
            }


            $openingPrice = Accounts::where('store_id', $store_id)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->whereNotNull('opening_price')
            ->sum('opening_price');

            if ($openingPrice) {
                $openingPrice = $openingPrice;
            } else {
                $openingPrice = 0;
            }
            $weeklySale = Accounts::where('store_id', $store_id)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->sum('price');
            $totalSale = $openingPrice ?? 0 + $weeklySale;



            return response()->json([
                'success' => true,
                'message' => 'Weekly Sale List',
                'data' => [
                    'data' => $weeklySales->items(),
                    'start_date' => $startOfWeek,
                    'end_date' => $endOfWeek,
                    'opening_price' => $openingPrice ?? 0,
                    'weekly_sale' => $weeklySale ?? 0,
                    'total_amount' => $totalSale ?? 0,
                ],
                'start_date' => $startOfWeek,
                'end_date' => $endOfWeek,
                'opening_price' => $openingPrice ?? 0,
                'weekly_sale' => $weeklySale ?? 0,
                'total_amount' => $totalSale ?? 0,
                'meta' => [
                    'total' => $weeklySales->total(),
                    'per_page' => $weeklySales->perPage(),
                    'total_pages' => $weeklySales->lastPage(),
                    'current_page' => $weeklySales->currentPage(),
                    'last_page' => $weeklySales->lastPage(),
                    'from' => $weeklySales->firstItem(),
                    'to' => $weeklySales->lastItem(),
                ],
                'links' => [
                    'first_page_url' => $weeklySales->url(1),
                    'last_page_url' => $weeklySales->url($weeklySales->lastPage()),
                    'next_page_url' => $weeklySales->nextPageUrl(),
                    'prev_page_url' => $weeklySales->previousPageUrl(),
                ],
            ], 200);


        }


        public function monthly_sale_list(Request $request, $store_id)
        {
            $store_id=$request->store_id;
            // Get the start and end dates of the current week
            $startOfMonth = now()->startOfMonth()->format('Y-m-d');
            $endOfMonth = now()->endOfMonth()->format('Y-m-d');

            // Retrieve the sale list for the current month
            $monthlySales = Accounts::where('store_id', $store_id)
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->select('name', 'quantity', 'price')
                ->whereNotNull('name');

            $monthlySales = $monthlySales->paginate(50);

            if ($monthlySales->isEmpty()) {
                return response()->json([
                    'data' => [],
                    'meta' => [
                        'current_page' => 0,
                        'last_page' => 0,
                        'per_page' => 10,
                        'total' => 0,
                    ],
                    'links' => [
                        'first_page_url' => null,
                        'last_page_url' => null,
                        'next_page_url' => null,
                        'prev_page_url' => null,
                    ],
                ]);
            }


        $openingPrice = Accounts::where('store_id', $store_id)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->whereNotNull('opening_price')
            ->sum('opening_price');

        if ($openingPrice) {
            $openingPrice = $openingPrice;
        } else {
            $openingPrice = 0;
        }
        $monthlySale = Accounts::where('store_id', $store_id)
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->sum('price');
        $totalSale = $openingPrice ?? 0  + $monthlySale;


            return response()->json([
                'success' => true,
                'message' => 'Monthly Sale List',

                'data' => [
                    'data' => $monthlySales->items(),
                    'start_date' => $startOfMonth,
                'end_date' => $endOfMonth,
                'opening_price' => $openingPrice ?? 0,
                'monthly_sale' => $monthlySale ?? 0,
                'total_amount' => $totalSale ?? 0,
                ],
                'start_date' => $startOfMonth,
                'end_date' => $endOfMonth,
                'opening_price' => $openingPrice ?? 0,
                'monthly_sale' => $monthlySale ?? 0,
                'total_amount' => $totalSale ?? 0,
                'meta' => [
                    'total' => $monthlySales->total(),
                    'per_page' => $monthlySales->perPage(),
                    'total_pages' => $monthlySales->lastPage(),
                    'current_page' => $monthlySales->currentPage(),
                    'last_page' => $monthlySales->lastPage(),
                    'from' => $monthlySales->firstItem(),
                    'to' => $monthlySales->lastItem(),
                ],
                'links' => [
                    'first_page_url' => $monthlySales->url(1),
                    'last_page_url' => $monthlySales->url($monthlySales->lastPage()),
                    'next_page_url' => $monthlySales->nextPageUrl(),
                    'prev_page_url' => $monthlySales->previousPageUrl(),
                ],
            ], 200);


        }





}
