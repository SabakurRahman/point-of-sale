<?php

namespace App\Models;

use App\Http\Requests\StorePackageRequest;
use App\Http\Requests\UpdatePackageRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $guarded = [];
    public const active = 1;
    public const inactive = 2;

    public const status = [
        self::active => 'active',
        self::inactive => 'inactive',
    ];



    public function createUserPackage(StorePackageRequest $request)
    {
        return self::query()->create($this->preparedPackage($request));
    }
    private function preparedPackage(StorePackageRequest $request)
    {
        return [
            'tagline' => $request->tagline,
            'quota'   => $request->quota,
            'price'   => $request->price,
            'plan'    => $request->plan,
            'status'  => $request->status ?? self::active,
        ];


    }
    public function updateUserPackage(UpdatePackageRequest $request, Package $package)
    {
         $package->update($this->updatePackage($request,$package));
         return $package;
    }
    private function updatePackage(UpdatePackageRequest $request, Package $package)
    {
        $data = [
            'tagline' => $request->tagline ?? $package->tagline,
            'quota'   => $request->quota   ?? $package->quota,
            'price'   => $request->price   ?? $package->price,
            'plan'    => $request->plan   ??  $package->plan,
            'status'  => $request->status ?? $package->status,
        ];
        return $data;
    }

    public function features()
    {
        return $this->belongsToMany(Feature::class, 'feature_package', 'package_id', 'feature_id');
    }



}
