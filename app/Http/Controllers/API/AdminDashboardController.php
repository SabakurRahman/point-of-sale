<?php

namespace App\Http\Controllers;
namespace App\Http\Controllers\API;
use App\Http\Resources\TopCustomerResource;
use App\Http\Resources\TopSellingProductResource;

use App\Http\Resources\CustomerListResource;
use App\Models\Customer;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Support\Facades\Validator;

class AdminDashboardController extends Controller
{


    function admin_dashboard_data($store_id)
    {
        $currentYear  = date('Y');
        $currentMonth = date('m');
        $startDate    = Carbon::create($currentYear, $currentMonth, 1)->startOfMonth();
        $endDate      = Carbon::create($currentYear, $currentMonth, 1)->endOfMonth();
        // dd($startDate);
        // dd( $endDate);
        $totalSaleProductNumberMonth = OrderItem::whereHas('order', function ($query) use ($startDate, $endDate, $store_id) {
            $query->whereBetween('created_at', [$startDate, $endDate])
                ->where('store_id', $store_id);
        })->sum('quantity');
        // dd($totalSaleProductNumberMonth);//62

        $totalSaleProductNumber = OrderItem::whereHas('order', function ($query) use ($store_id) {
            $query->where('store_id', $store_id);
        })->sum('quantity');
        // dd($totalSaleProductNumber);//66
        $allOrdersOfStore = Order::where('store_id', $store_id)->get();
        $totalOrder       = $allOrdersOfStore->count();

        $totalProduct                = Product::where('store_id', $store_id)->sum('quantity');
        $total_paid_order_amount     = $allOrdersOfStore->sum('total_amount') - $allOrdersOfStore->sum('discount');
        $total_order_amount          = $allOrdersOfStore->sum('total_amount');
        $total_order_discount_amount = $allOrdersOfStore->sum('discount');

        // Calculate total sale product by day for the current month

        $totalSaleProductByDay = OrderItem::selectRaw('DATE_FORMAT(created_at, "%d-%b-%Y") as date, SUM(quantity) as total_sale_product')
            ->whereHas('order', function ($query) use ($startDate, $endDate, $store_id) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                    ->where('store_id', $store_id);
            })
            ->groupBy('date')
            ->get();
        // $customers = Order::where('store_id', $store_id)->distinct('customer_phone')->count('customer_phone');
        $customers = Customer::query()->where('store_id', $store_id)->count();
        $topCustomers = Customer::where('store_id',$store_id)->withCount('order')->has('order')
            ->get()
            ->sortByDesc(function ($customer) {
                return $customer->order->sum('total_amount');
            })
            ->take(10);

        $topCustomerFormattedData = TopCustomerResource::collection($topCustomers);

        $topSellingProducts = Product::with(['orderitems'])->where('store_id',$store_id) // Eager load related data
        ->get()
            ->map(function ($product) {
                $totalQuantity = $product?->orderitems->sum('quantity');
                $totalPrice = $product?->orderitems->sum(function ($orderItem) {
                    return $orderItem->quantity * $orderItem->unit_price;
                });
                $product->totalQuantity = $totalQuantity;
                $product->totalPrice = $totalPrice;
                return $product;
            })
            ->sortByDesc('totalQuantity')
            ->take(10);
        $topSellingProducts = TopSellingProductResource::collection($topSellingProducts);
        return response()->json([
            'success' => true,
            'message' => null,
            'data' => [
                'total_sale_product_number' => $totalSaleProductNumber,
                'total_sale_product_number_by_month' => $totalSaleProductNumberMonth,
                'total_order_count' => $totalOrder,
                'total_sale_product_by_day' => $totalSaleProductByDay,
                'total_customers' => $customers,
                'total_totalProduct_stock' => $totalProduct,
                'total_order_amount' => $total_order_amount,
                'total_paid_order_amount' => $total_paid_order_amount,
                'total_order_discount_amount' => $total_order_discount_amount,
                'top_customer_list' => $topCustomerFormattedData,
                'top_selling_product_list' => $topSellingProducts
            ]
        ], 200);

    }

    public function topCustomer($store_id){
        $topCustomers = Customer::where('store_id',$store_id)->withCount('order')->has('order')
            ->get()
            ->sortByDesc(function ($customer) {
                $totalAmount = $customer->order->sum('total_amount');
                $totalDiscount = $customer->order->sum('discount');
                $netAmount = $totalAmount - $totalDiscount;
                return $netAmount;
            })
            ->take(10);

        return response()->json([
            'success' => true,
            'message' => 'top ten customer list',
            'data' => TopCustomerResource::collection($topCustomers)


        ], 200);

    }

    public function topProduct($store_id){
        $topSellingProducts = Product::with(['orderitems'])->where('store_id',$store_id) // Eager load related data
        ->get()
            ->map(function ($product) {
                $totalQuantity = $product?->orderitems->sum('quantity');
                $totalPrice = $product?->orderitems->sum(function ($orderItem) {
                    return $orderItem->quantity * $orderItem->unit_price;
                });
                $product->totalQuantity = $totalQuantity;
                $product->totalPrice = $totalPrice;
                return $product;
            })
            ->sortByDesc('totalQuantity')
            ->take(10);
        return response()->json([
            'success' => true,
            'message' => 'top ten sell product list',
            'data' => TopSellingProductResource::collection($topSellingProducts)
        ], 200);

    }


    function customers($store_id,Request $request)
    {
        $paginate = $request->input('per_page') ?? 10;
        $query = Customer::query()
            ->with(['membership_card', 'membership_card.membership_card_type'])
            ->where('store_id', $store_id);

        $searchTerm = $request->input('search');
        $searchType = $request->input('type');

        if ($searchTerm && $searchType) {
            switch ($searchType) {
                case 'name':
                    $query->where('name', 'like', "%$searchTerm%");
                    break;
                case 'phone':
                    $query->where('phone', 'like', "%$searchTerm%");
                    break;
                case 'address':
                    $query->where('address', 'like', "%$searchTerm%");
                    break;
                case 'membership_card_id':
                    $query->where('membership_card_id', $searchTerm);
                    break;
                case 'status':
                    $query->where('status', $searchTerm);
                    break;
            }
        }


        if ($request->input('sort_by') && $request->input('sort_direction')) {
            $query->orderBy($request->input('sort_by'), $request->input('sort_direction'));
        } else {
            $query->orderByDesc('id');
        }

        $customers = $query->paginate($paginate);


        $customers_formatted = CustomerListResource::collection($customers)->response()->getData();

        $total =  Customer::query()
            ->with(['membership_card', 'membership_card.membership_card_type'])
            ->where('store_id', $store_id)->count();

        return response()->json([
            'success' => true,
            'message' => 'Customer List',
            'data'    => [
                            "customer"=> $customers_formatted,
                            "total" => $total
            ],
            'meta'    => [
                'total'        => $customers->total(),
                'per_page'     => $customers->perPage(),
                'total_pages'  => $customers->lastPage(),
                'current_page' => $customers->currentPage(),
                'last_page'    => $customers->lastPage(),
                'from'         => $customers->firstItem(),
                'to'           => $customers->lastItem(),
            ],
            'links'   => [
                'first_page_url' => $customers->url(1),
                'last_page_url'  => $customers->url($customers->lastPage()),
                'next_page_url'  => $customers->nextPageUrl(),
                'prev_page_url'  => $customers->previousPageUrl(),
            ],
        ], 200);

    }


    function totalSaleProductNumberMonth($store_id, $startDate, $endDate = null)
    {
        $currentYear  = date('Y');
        $currentMonth = date('m');

        if (empty($endDate)) {
            $endDate = Carbon::create($currentYear, $currentMonth, 1)->endOfMonth();
        }

        $query      = OrderItem::whereHas('order', function ($query) use ($startDate, $endDate, $store_id) {
            $query->whereBetween('created_at', [$startDate, $endDate])
                ->where('store_id', $store_id)->with('product');

        });
        $orderItems = $query->paginate(10);


        $totalSaleProductNumberMonth = $orderItems->getCollection()->map(function ($orderItem) {
            return [
                'id'           => $orderItem->id,
                'order_id'     => $orderItem->order_id,
                'product_name' => $orderItem->product->name,
                'quantity'     => $orderItem->quantity,
                'unit_price'   => $orderItem->unit_price,
                'total_price'  => $orderItem->total_price,
                'created_at'   => $orderItem->created_at,
                'updated_at'   => $orderItem->updated_at,
            ];
        });


        return response()->json([
            'success' => true,
            'message' => 'Date Search List',
            'data'    => $totalSaleProductNumberMonth,
            'meta'    => [
                'total'        => $orderItems->total(),
                'per_page'     => $orderItems->perPage(),
                'total_pages'  => $orderItems->lastPage(),
                'current_page' => $orderItems->currentPage(),
                'last_page'    => $orderItems->lastPage(),
                'from'         => $orderItems->firstItem(),
                'to'           => $orderItems->lastItem(),
            ],
            'links'   => [
                'first_page_url' => $orderItems->url(1),
                'last_page_url'  => $orderItems->url($orderItems->lastPage()),
                'next_page_url'  => $orderItems->nextPageUrl(),
                'prev_page_url'  => $orderItems->previousPageUrl(),
            ],
        ], 200);


    }

    function totalSaleProductNumber($store_id)
    {
        $query = OrderItem::whereHas('order', function ($query) use ($store_id) {
            $query->where('store_id', $store_id);
        })->with('product');

        $orderItems = $query->paginate(10);

        $transformedOrderItems = $orderItems->getCollection()->map(function ($orderItem) {
            return [
                'id'           => $orderItem->id,
                'order_id'     => $orderItem->order_id,
                'product_name' => $orderItem->product->name,
                'quantity'     => $orderItem->quantity,
                'unit_price'   => $orderItem->unit_price,
                'total_price'  => $orderItem->total_price,
                'created_at'   => $orderItem->created_at,
                'updated_at'   => $orderItem->updated_at,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Total Sale Product List',
            'data'    => $transformedOrderItems,
            'meta'    => [
                'total'        => $orderItems->total(),
                'per_page'     => $orderItems->perPage(),
                'total_pages'  => $orderItems->lastPage(),
                'current_page' => $orderItems->currentPage(),
                'last_page'    => $orderItems->lastPage(),
                'from'         => $orderItems->firstItem(),
                'to'           => $orderItems->lastItem(),
            ],
            'links'   => [
                'first_page_url' => $orderItems->url(1),
                'last_page_url'  => $orderItems->url($orderItems->lastPage()),
                'next_page_url'  => $orderItems->nextPageUrl(),
                'prev_page_url'  => $orderItems->previousPageUrl(),
            ],
        ], 200);
    }


    function totalOrder($store_id)
    {

        $query = Order::where('store_id', $store_id)->orderBy('id', 'desc');

        $totalOrder = $query->paginate(10);
        foreach ($totalOrder as $order) {
            $order->total_amount_after_discount = $order->total_amount - $order->discount;
            $order->order_date                  = $order->created_at->toDayDateTimeString();
            if (is_numeric($order->payment_method)) {
                $order->payment_method = !empty($order->payment_method) ? Order::PAYMENT_METHOD_LIST[$order->payment_method] : null;
            } else {
                $order->payment_method = 'Not selected';
            }
        }


        return response()->json([
            'success' => true,
            'message' => 'Order List',
            'data'    => $totalOrder,
            'meta'    => [
                'total'        => $totalOrder->total(),
                'per_page'     => $totalOrder->perPage(),
                'total_pages'  => $totalOrder->lastPage(),
                'current_page' => $totalOrder->currentPage(),
                'last_page'    => $totalOrder->lastPage(),
                'from'         => $totalOrder->firstItem(),
                'to'           => $totalOrder->lastItem(),
            ],
            'links'   => [
                'first_page_url' => $totalOrder->url(1),
                'last_page_url'  => $totalOrder->url($totalOrder->lastPage()),
                'next_page_url'  => $totalOrder->nextPageUrl(),
                'prev_page_url'  => $totalOrder->previousPageUrl(),
            ],
        ], 200);

    }



}
