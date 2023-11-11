<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    //public $fillable = ['name', 'price', 'quantity', 'description', 'image', 'category_id', 'store_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function orderitems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getServiceByCategory($category_id)
    {
        return Product::query()->select('id', 'name', 'price', 'image', 'category_id', 'status', 'stock')
            ->where('variant', 2)
            ->where('category_id', $category_id)
            ->where('status', 'active')
            ->where('stock', '>', 0)
            ->get();
    }

    public function getServiceProduct($request, $store_id, $category_id)
    {
        return Product::query()
            ->where('variant', 2)
            ->where('store_id', $store_id)
            ->where('category_id', $category_id)
            ->get();
    }

    public function product_features()
    {
        return $this->hasMany(ProductFeature::class)->orderBy('serial');
    }


}
