<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\AllOrderResource;
use App\Http\Resources\OrderDetailsAllResource;
use App\Http\Resources\OrderDetailsResource;
use App\Http\Resources\TransactionListResource;
use App\Models\Customer;
use App\Models\MembershipCard;
use App\Models\Transaction;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Accounts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Throwable;

class PlaceOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($store_id = null)
    {
        $user = Auth::user();

        if ($user->store_id !== null) {
            $store_id = $user->store_id;
        }

        $orders = Order::with('customer','customer.order')->where('store_id', $store_id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        foreach ($orders as $order) {
            $order->total_amount_after_discount = $order->total_amount - $order->discount;
            $order->order_date = $order->created_at->toDayDateTimeString();
            $order->total_order=$order?->customer?->order?->count();
            if (is_numeric($order->payment_method)) {
                $order->payment_method = Order::PAYMENT_METHOD_LIST[$order->payment_method];
            } else {
                $order->payment_method = 'Not selected';
            }


        }

        return response()->json([
            'success' => true,
            'message' => 'Orders list',
            'data' => $orders->items(),
            'meta' => [
                'orders_pagination' => [
                    'total' => $orders->total(),
                    'per_page' => $orders->perPage(),
                    'total_pages' => $orders->lastPage(),
                    'current_page' => $orders->currentPage(),
                    'last_page' => $orders->lastPage(),
                    'from' => $orders->firstItem(),
                    'to' => $orders->lastItem(),
                ],
            ],
            'links' => [
                'orders_first_page_url' => $orders->url(1),
                'orders_last_page_url' => $orders->url($orders->lastPage()),
                'orders_next_page_url' => $orders->nextPageUrl(),
                'orders_prev_page_url' => $orders->previousPageUrl(),
            ],
        ], 200);
    }


    /**
     * Show the form for creating a new resource.
     */


    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateOrder(Request $request, string $id)
    {
        // Authenticate the store user
        $user = Auth::user();

        // Find the order
        $order = Order::find($id);

        // Check if the order exists
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
                'data' => null
            ], 404);
        }

        // Check if the order belongs to the store
        if ($order->store_id != $user->userStores[0]->store_id) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
                'data' => null
            ], 404);
        }

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'order_status' => 'required|string|in:pending,processing,completed,cancelled',
            'payment_status' => 'required|string|in:pending,processing,completed,cancelled'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(),
                'data' => null
            ], 400);
        }

        // Update the order status
        $order->order_status = $request->input('order_status');
        $order->payment_status = $request->input('payment_status');
        //if order is cancelled then update the product quantity
        if ($request->input('order_status') == 'cancelled') {
            foreach ($order->orderItems as $orderItem) {
                $product = Product::find($orderItem->product_id);
                $product->quantity = $product->quantity + $orderItem->quantity;
                $product->stock = $product->stock + $orderItem->quantity;
                $product->save();
            }


        }

        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Order updated successfully',
            'data' => $order
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyOrder(string $id)
    {
        // Authenticate the store user
        $user = Auth::user();

        // Find the order
        $order = Order::find($id);

        // Check if the order exists
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
                'data' => null
            ], 404);
        }

        // Check if the order belongs to the store
        if ($order->store_id != $user->userStores[0]->store_id) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
                'data' => null
            ], 404);
        }

        // Delete the order
        $order->delete();

        return response()->json([
            'success' => true,
            'message' => 'Order deleted successfully',
            'data' => null
        ], 200);
    }


    /**
     * @throws ValidationException
     */
    public function placeOrder(Request $request,$store_id = null)
    {

        if (empty($store_id)){
            $store_id = auth()->user()->store_id;
        }

        Log::info('ORDER_PLACE', [$request->all()]);
        // Authenticate the store user
        $user = Auth::user();
        if ($user->parent_id) {
            $parentUser = User::find($user->parent_id);
            if ($parentUser) {
                $user = $parentUser;
            }
        }

        //find Product
//        $product = Product::find($request->input('products')[0]['id']);

        // Validate the request data

        $rules = [
            'customer_name' => 'nullable|string',
            'customer_phone' => 'nullable|string',
            'customer_address' => 'nullable|string',
            'customer_email' => 'nullable|email',
            //'payment_method'      => 'required',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'given_amount' => 'required|numeric',
        ];

        if (!$request->is_split) {
            $rules = array_merge($rules, ['payment_method' => 'required']);
        }


        $validator = Validator::make($request->all(), $rules);


        if ($request->input('given_amount') < 1) {
            throw ValidationException::withMessages(['given_amount' => 'Given amount is required']);
        }

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(),
                'data' => null
            ], 400);
        }


        if (count($request->input('products')) < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Please add product or service to the order',
                'data' => null
            ], 400);
        }

        $totalAmount = 0;
        $quantity_validation = false;
        foreach ($request->input('products') as $productData) {
            $totalAmount += $productData['price'] * $productData['quantity'];
            $product = Product::find($productData['id']);
//            if ($product->variant !== null) {
//                continue;
//            }
            if ($product->quantity < $productData['quantity']) {
                $quantity_validation = true;
                break;
            }
        }

        if ($quantity_validation) {
            return response()->json([
                'success' => false,
                'message' => 'Product is not available. Please check the quantity',
                'data' => null
            ], 400);
        }

        $customer = null;
        $membership_card = null;
        if ($request->has('customer_phone') && !empty($request->input('customer_phone'))) {
            $customer = (new Customer())->getCustomerByPhone($request->input('customer_phone'), $user->userStores[0]->store_id);
        }


        if ($request->has('customer_card') && !empty($request->input('customer_card'))) {
            $membership_card = (new MembershipCard())->getMembershipCardByCardNumber($request->input('customer_card'), $user->userStores[0]->store_id);
        }
        // dd($user->userStores[0]->store_id);


        // Create a new order
        $order = new Order();

        //accessing store id from user
        $order->store_id = $store_id;
        $order->order_date = now();
        $order->user_id = Auth::id();
        $order->customer_name = $request->input('customer_name');
        $order->customer_phone = $request->input('customer_phone');
        $order->customer_address = $request->input('customer_address');
        $order->customer_email = $request->input('customer_email');
        $order->total_amount = $totalAmount;
        //$order->payment_method     = $request->input('payment_method');
        $order->trx_id = $request->input('trxId');
        $order->discount = $request->input('discount');
        $order->given_amount = $request->input('given_amount');
        $order->changed_amount = $request->input('changed_amount');
        $order->customer_id = $customer?->id;
        $order->membership_card_id = $membership_card;


        $order->save();


        // Process the order items
        foreach ($request->input('products') as $productData) {
            $product = Product::find($productData['id']);

            // Create an order item
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $product->id;
            $orderItem->quantity = $productData['quantity'];
            $orderItem->changed_price = $productData['price'] * $productData['quantity'];
            $orderItem->unit_price = $product->price;
            $orderItem->total_price = $product->price * $productData['quantity'];
            $orderItem->save();

            $account = new Accounts();
            $account->store_id = $order->store_id;
            $account->name = $product->name;
            $account->quantity = $productData['quantity'];
            $account->price = $product->price * $productData['quantity'];
            $account->save();
        }


        // Update the product quantities
        foreach ($request->input('products') as $productData) {
            $product = Product::find($productData['id']);

            if ($product->variant == null) {
                $product->quantity = $product->quantity - $productData['quantity'];
                $product->stock = $product->stock - $productData['quantity'];
            }

            $product->save();
        }


        if ($request->is_split) {
            foreach ($request->input('payments') as $payments) {
                $transaction = (new Transaction())->createTransactionDetails($payments, $order);

            }
        } else {
            $transaction = (new Transaction())->createTransactionDetails($request->all(), $order);
        }



        return response()->json([
            'success' => true,
            'message' => 'Order Placed successfully',
            'data' => [
                'order' => $order,
                'store_name' => Store::find($user->userStores[0]->store_id)->name,
                'store_address' => Store::find($user->userStores[0]->store_id)->address,
                'store_phone' => Store::find($user->userStores[0]->store_id)->phone,
            ]
        ], 201);

        //
    }


    public function showOrder(string $order_id)
    {
        // Authenticate the store user
        $user = Auth::user();

        // Find the order
        $order = Order::find($order_id);

        // Check if the order exists
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
                'data' => null
            ], 404);
        }

        // Check if the order belongs to the store
        if ($order->store_id != $user->userStores[0]->store_id) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
                'data' => null
            ], 404);
        } else {
            // Load the order items
            $order->orderItems;
        }

        // Modify the payment method text if it's 1 for bkash 2 for cash in hand
        if ($order->payment_method == 1) {
            $paymentMethod = 'Cash On Hands';
        } else if ($order->payment_method == 2) {
            $paymentMethod = 'Bkash/Nagad';
        } else {
            $paymentMethod = 'Card';
        }

        return response()->json([
            'success' => true,
            'message' => null,
            'data' => [
                'id' => $order->id,
                'store_id' => $order->store_id,
                'order_date' => $order->order_date,
                'user_id' => $order->user_id,
                'customer_name' => $order->customer_name,
                'customer_phone' => $order->customer_phone,
                'customer_email' => $order->customer_email,
                'customer_address' => $order->customer_address,
                'total_amount' => $order->total_amount,
                'payment_method' => $paymentMethod,
                'created_at' => $order->created_at,
                'updated_at' => $order->updated_at,
                'order_items' => $order->orderItems
            ]
        ], 200);
    }


    public function cancelOrder(string $id)
    {
        // Authenticate the store user
        $user = Auth::user();

        // Find the order
        $order = Order::find($id);

        // Check if the order exists
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
                'data' => null
            ], 404);
        }

        // Check if the order belongs to the store
        if ($order->store_id != $user->userStores[0]->store_id) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
                'data' => null
            ], 404);
        }


        // Cancel the order
        $order->order_status = 'cancelled';
        $order->payment_status = 'cancelled';
        $order->save();

        //sync product quantity
        foreach ($order->orderItems as $orderItem) {
            $product = Product::find($orderItem->product_id);
            $product->quantity = $product->quantity + $orderItem->quantity;
            $product->stock = $product->stock + $orderItem->quantity;
            $product->save();
        }


        return response()->json([
            'success' => true,
            'message' => 'Order cancelled successfully',
            'data' => $order
        ], 200);
    }


    public function getOrderDetails(int $store_id, int $order_id)
    {
        $order = Order::query()->with(['orderitems', 'orderitems.product', 'customer'])->where([
            'store_id' => $store_id,
            'id' => $order_id
        ])->first();

        return response()->json([
            'success' => true,
            'message' => 'Order data fetched successfully',
            'data' => [
                'order' => new OrderDetailsResource($order),
            ]


        ], 201);
    }

    public function orderDetails($order_id)
    {

        $order = (new Order())->getOrderAllDetails($order_id);

        // dd($order);

//        if (Auth::check() && Auth::user()->type === 'admin') {
//            $admin = Auth::user();
//        }
        // dd($admin);
        return response()->json([
            'success' => true,
            'message' => 'Order details retrieved successfully',
            'data' => [  //$order
                'order' => new OrderDetailsAllResource($order),
            ]
        ], 200);


    }

    public function deleteOrder($store_id, $order_id)
    {
        // dd($store_id, $order_id);
        $message = 'Order and associated data deleted successfully';
        try {
            DB::beginTransaction();
            $is_deleted = (new Order())->deleteOrder($store_id, $order_id);

            if (!$is_deleted) {
                $message = 'Oder does\'t exists';
            }
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            Log::info('PRODUCT_DATA_NOT_SAVED', ['message' => $throwable]);
        }
        return response()->json([
            'success' => true,
            'message' => $message,
        ], 200);

    }

    public function getPaymentMethod()
    {
        try {
            $payment_method =  (new Order())->paymentMethod();
//            dd($payment_method);
            $success = true;
            $message = 'Payment method retrieve successfully';
        } catch (\Throwable $throwable) {
            $success = false;
            $message = 'Failed! ' . $throwable->getMessage();
        }
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data'    => $payment_method
        ]);
    }


}
