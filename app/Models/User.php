<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'parent_id',
        'email',
        'address',
        'password',
        'type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */


    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

     public function getJWTIdentifier() {
        return $this->getKey();
    }
    public function getJWTCustomClaims() {
        return [];
    }


    public function products(){
        return $this->hasMany(Product::class);
    }
    public function categories(){
        return $this->hasMany(Category::class);
    }
    public function orders(){
        return $this->belongsToMany(Order::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }



    public function stores()
    {
        return $this->hasMany(Store::class);
    }

    public function userStores()
    {
        return $this->hasMany(UserStore::class);
    }

    public function user_stores()
    {
        return $this->belongsToMany(Store::class, 'user_stores');
    }

}
