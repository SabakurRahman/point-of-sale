<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // public $fillable = ['name', 'image', 'parent_id'];

    public function products(){
        return $this->hasMany(Product::class);
    }
    public function web_products(){
        return $this->hasMany(Product::class)->where('is_show_on_web', 1)->whereNotNull('is_show_on_web');
    }

    public function stores(){
        return $this->hasMany(Store::class);
    }

    public function serviceCategory()
    {
        return $this->belongsTo(ServiceCategory::class);
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }



}
