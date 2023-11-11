<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Feature extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const active = 1;
    public const inactive = 2;

    public const status = [
        self::active => 'active',
        self::inactive => 'inactive',
    ];



    public function createFeature(Request $request)
    {
        return self::query()->create($this->preparedFeature($request));
    }
    private function preparedFeature(Request $request)
    {
       $s = self::query()->max('serial');
        return [
            'feature' => $request->feature,
            'serial'  => $s+1,
            'status'  => $request->status ?? self::active,
        ];
    }

    public function updateFeature(Request $request, Feature $feature)
    {
        return $feature->update($this->updateFeatureData($request, $feature));
    }

    private function updateFeatureData(Request $request, Feature $feature)
    {
        $data = [
            'feature' => $request->feature ?? $feature->feature,
            'serial'  => $request->serial ?? $feature->serial,
            'status'  => $request->status ?? $feature->status,
        ];
        return $data;
    }


    public function getFeatures()
    {
        return self::query()->orderBy('serial')->get();
    }

    public function rearrange(Request $request)
    {
        $data = $request->all();
        foreach ($data as $value){
            $feature = self::query()->find($value['id']);
            $feature->update(['serial' => $value['serial']]);
        }
        return true;


    }





    public function packages()
    {
        return $this->belongsToMany(Package::class, 'feature_package', 'feature_id', 'package_id');
    }



}
