<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStore extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'store_id',
    ];

    public function stores()
{
    return $this->belongsToMany(Store::class);
}

public function users()
{
    return $this->belongsToMany(User::class);
}



}
