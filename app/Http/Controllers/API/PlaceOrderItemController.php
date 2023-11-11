<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlaceOrderItemController extends Controller
{
    public function showOrderItem($id)
    {
        $user = Auth::user();
        $order = Order::find($id);
        if($order){
            if($order->store_id == $user->userStores[0]->store_id){
                $orderItems = OrderItem::where('order_id', $id)->get();
                if($orderItems){
                    return response()->json([
                        'success' => true,
                        'message' => 'Order Item List',
                        'data' => $orderItems
                    ], 200);
                }else{
                    return response()->json([
                        'success' => false,
                        'message' => 'Order Item Not Found',
                        'data' => ''
                    ], 404);
                }
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Order Not Found',
                    'data' => ''
                ], 404);
            }
        }
    
    }
    
    public function placeOrderItem(Request $request, $id)
    {
        $user = Auth::user();
        $order = Order::find($id);
        if($order){
            if($order->store_id == $user->userStores[0]->store_id){
                $orderItem = new OrderItem();
                $orderItem->order_id = $id;
                $orderItem->product_id = $request->product_id;
                $orderItem->quantity = $request->quantity;
              
                $orderItem->price = $request->price;
                $orderItem->save();
            
                return response()->json([
                    'success' => true,
                    'message' => 'Order Item Created',
                    'data' => $orderItem
                ], 201);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Order Not Found',
                    'data' => ''
                ], 404);
            }
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Order Not Found',
                'data' => ''
            ], 404);
        }
    }

    public function updateOrderItem(Request $request, $id)
    {
        $user = Auth::user();
        $order = Order::find($id);
        if($order){
            if($order->store_id == $user->userStores[0]->store_id){
                $orderItem = OrderItem::find($request->id);
                if($orderItem){
                    $orderItem->order_id = $id;
                    $orderItem->product_id = $request->product_id;
                    $orderItem->quantity = $request->quantity;


                    $orderItem->price = $request->price;
                    $orderItem->save();
                    return response()->json([
                        'success' => true,
                        'message' => 'Order Item Updated',
                        'data' => $orderItem
                    ], 200);
                }else{
                    return response()->json([
                        'success' => false,
                        'message' => 'Order Item Not Found',
                        'data' => ''
                    ], 404);
                }
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Order Not Found',
                    'data' => ''
                ], 404);
            }
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Order Not Found',
                'data' => ''
            ], 404);
        }
    }

    public function deleteOrderItem($id)
    {
        $user = Auth::user();
        $order = Order::find($id);
        if($order){
            if($order->store_id == $user->userStores[0]->store_id){
                $orderItem = OrderItem::find($id);
                if($orderItem){
                    $orderItem->delete();
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Order Item Deleted',
                        'data' => $orderItem
                    ], 200);
                }else{
                    return response()->json([
                        'success' => false,
                        'message' => 'Order Item Not Found',
                        'data' => ''
                    ], 404);
                }
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Order Not Found',
                    'data' => ''
                ], 404);
            }
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Order Not Found',
                'data' => ''
            ], 404);
        }
    }

}


