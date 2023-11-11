<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Http\Requests\StorePaymentMethodRequest;
use App\Http\Requests\UpdatePaymentMethodRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($store_id)
    {
        $payment = (new PaymentMethod())->getAllPayment($store_id);
        // dd($post);
        return response()->json([
            "success" => true,
            "message" => "Payment Method Retrieve Successfully",
            "data"    =>$payment ,

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentMethodRequest $request,$store_id)
    {
        try {
            Log::info('POST_CATEGORY_DATA', ['data', $request->all()]);
            $payment_method= (new PaymentMethod())->createPaymentMethod($store_id,$request);
            $success = true;
            $message="Payment method created";
        } catch (\Throwable $throwable) {
            $success = false;
            $message = 'Failed' . $throwable->getMessage();
            Log::info('PAYMENT_METHOD_CREATED_FAILED', ['ERROR', $throwable]);
        }
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data'    => $payment_method
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show($store_id,PaymentMethod $paymentMethod):JsonResponse
    {
        $paymentMethod = (new PaymentMethod())->findPaymentMethodById($store_id,$paymentMethod);

        if (!$paymentMethod) {
            return response()->json([
                "success" => false,
                "message" => "Payment Method not found",
            ], 404);
        }

        return response()->json([
            "success" => true,
            "message" => "Payment Method By Id",
            "data" => $paymentMethod,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentMethodRequest $request,$store_id,PaymentMethod $paymentMethod)
    {
        try {
            Log::info('PAYMENT_METHOD_DATA', ['data', $request->all()]);
            $updatePaymentMethod = (new PaymentMethod())->updatePaymentMethod($request,$store_id,$paymentMethod);
            $success = true;
            $message="Payment Method Updated";
        } catch (\Throwable $throwable) {
            $success = false;
            $message = 'Failed' . $throwable->getMessage();
            Log::info('PAYMENT_METHOD_UPDATED_FAILED', ['ERROR', $throwable]);
        }
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data'    => $updatePaymentMethod
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($store_id,PaymentMethod $paymentMethod)
    {
        try {
            DB::beginTransaction();
            $paymentMethod->delete();
            $success = true;
            $message = 'Payment Method Deleted Successfully';
            DB::commit();
        } catch (\Throwable $throwable) {
            DB::rollBack();
            $success = false;
            $message = 'Failed! ' . $throwable;
        }
        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }
}

