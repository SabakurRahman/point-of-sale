<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'address',
        'description',
        'user_id',
    ];


    public function products(){
        return $this->hasMany(Product::class);
    }

    public function categories(){
        return $this->hasMany(Category::class);
    }

    public function orders(){
        return $this->belongsToMany(Order::class);
    }


    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
