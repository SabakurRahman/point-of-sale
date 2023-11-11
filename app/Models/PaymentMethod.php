<?php

namespace App\Models;

use App\Http\Requests\StorePaymentMethodRequest;
use App\Http\Requests\UpdatePaymentMethodRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;


class PaymentMethod extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getAllPayment($store_id)
    {
        return self::query()->where('store_id', $store_id)->get();
    }

    public function createPaymentMethod($store_id, StorePaymentMethodRequest $request)
    {
        return self::query()->create($this->preparePaymentMethodData($store_id, $request));

    }

    private function preparePaymentMethodData($store_id, StorePaymentMethodRequest $request)
    {
        $filename=Str::slug($request->input('name')).'.webp';
        if ($request->logo) {
            $file = $request->logo;

            Image::make($file)
                ->save(public_path('uploads/payment_method/') . $filename, 50, 'webp');

        }


        return [
            'store_id' => $store_id,
            'name' => $request->input('name'),
            'account_no' => $request->input('account_no'),
            'status' => 1,
            'logo' => $filename
        ];
    }


    public function findPaymentMethodById($store_id,PaymentMethod $paymentMethod)
    {
        return self::query()->where('store_id',$store_id)->find($paymentMethod);
    }

    public function updatePaymentMethod(UpdatePaymentMethodRequest $request, $store_id, PaymentMethod $paymentMethod)
    {
        if ($request->hasFile('logo')) {

            if ($paymentMethod->logo) {
                $oldImagePath = public_path('uploads/payment_method/') . $paymentMethod->logo;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
        }


            $filename=Str::slug($request->input('name')).'.webp';
            if ($request->logo) {
                $file = $request->logo;

                Image::make($file)
                    ->save(public_path('uploads/payment_method/') . $filename, 50, 'webp');

            }


        $paymentMethodData= [
            'store_id'      => $store_id,
            'name'          => $request->input('name') ?? $paymentMethod->name,
            'account_no'    => $request->input('account_no') ?? $paymentMethod->account_no,
            'status'        => $request->input('status') ?? $paymentMethod->status,
            'logo'          => $filename ?? $paymentMethod->logo
        ];

        return  $paymentMethod->update($paymentMethodData);

    }
}
