<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ServiceCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'duration',
        'store_id',
    ];


    public function categories()
    {
        return $this->hasMany(Category::class);
    }






    public  function serviceCategoryWithCategory(){

       return self::with('categories')->get();

    }


    public function getServiceCategory(){
       return $serviceCategories = self::select('id', 'name')->get();
    }

    public function saveServiceCategory(Request $request, $store_id)
    {
        return self::query()->create($this->prepairServiceCategoryData($request, $store_id));
    }

    public function serviceCategory($store_id, Request $request)
    {
        $query=  self::query()->where('store_id', $store_id);
        $paginate = $request->input('per_page') ?? 10;

        $searchTerm = $request->input('search');
        $searchType = $request->input('type');

        if ($searchTerm && $searchType) {
            switch ($searchType) {
                case 'name':
                    $query->where('name', 'like', "%$searchTerm%");
                    break;
                case 'duration':
                    $query->where('duration', 'like', "%$searchTerm%");
                    break;
                case 'status':
                    $query->where('status', $searchTerm);
                    break;
            }
        }


        if ($request->input('sort_by') && $request->input('sort_direction')) {
            $query->orderBy($request->input('sort_by'), $request->input('sort_direction'));
        } else {
            $query->orderByDesc('id');
        }
        $service_category = $query->paginate($paginate);

        $service_category_count =  self::query()->where('store_id', $store_id)->count();

        return [
            "service_category"         => $service_category,
            "total"                    => $service_category_count
        ];



    }

    public function updateServiceCategory(Request $request,$store_id,ServiceCategory $service_category)
    {
        $service_category_data = [
            'name' => $request->input('name') ?? $service_category->name,
            'status' => $request->input('status') ?? $service_category->status,
            'duration' => $request->input('duration') ?? $service_category->duration,
            'store_id' => $store_id ?? $service_category->store_id,
        ];
        return $service_category->update($service_category_data);
    }

    public function deleteServiceCategory(ServiceCategory $serviceCategory)
    {
        return $serviceCategory->delete();
    }

    private function prepairServiceCategoryData($request, $store_id)
    {
        return [
            'name' => $request->input('name'),
            'status' => $request->input('status'),
//            'duration' => $request->input('duration'),
            'store_id' =>$store_id,
//            // Add any other fields you need for the appointment here

        ];
    }

    public function getServiceCategoryList($store_id)
    {
        return self::query()->where('store_id', $store_id)->get();

    }

    public function getServiceCategoryListByServiceCategory($store_id, $serviceCategory_id)
    {
     return self::query()->select('id','name','status','duration')->find($serviceCategory_id);
    }
}
