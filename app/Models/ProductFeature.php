<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use mysql_xdevapi\Collection;

class ProductFeature extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function storeProductFeature($product, Request $request)
    {
       // dd($request);
       //$product_data = [];
        foreach ($request->input('product_features') as $feature) {
//            $product_data[] = [
//                'product_id' => $product->id,
//                'feature' => $feature['name'],
//                'serial' => (int)$feature['serial'],
//                'status' => 1,
//            ];
            ProductFeature::create([
                'product_id' => $product->id,
                'name'       => $feature['name'],
                'serial'     => $feature['serial'],
                'status'     => 1,
            ]);

        }
     // self::query()->insert($product_data);
    }

    public function updateProductFeature($product, Request $request)
    {
        //dd($product);
        $product->product_features()->delete();
        $product_feature_data = [];
        foreach ($request->input('product_features') as $feature) {

            $product_feature_data[] = [
                'product_id' => $request->id ?? $product->id,
                'name' => $feature['name'] ??  $product->product_features()->name,
                'serial' => $feature['serial'] ??  $product->product_features()->serial,
                'status' => 1 ??  $product->product_features()->status,
            ];


        }
        $featureData = collect($product_feature_data)->map(function ($featureData){
            return  new ProductFeature($featureData);

        });

       // dd($featureData);
        $product->product_features()->saveMany($featureData);





    }
}
