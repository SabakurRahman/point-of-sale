<?php

namespace App\Http\Controllers;

use App\Http\Resources\ServiceCategoryListResource;
use App\Http\Resources\ServiceCategoryWithCategoryResource;
use App\Models\ServiceCategory;
use App\Http\Requests\StoreServiceCategoryRequest;
use Illuminate\Http\Request;


class ServiceCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($store_id,Request $request)
    {
        //
        $getservicecategory = (new ServiceCategory())->serviceCategory($store_id,$request);
        $service_category= ServiceCategoryListResource::collection($getservicecategory['service_category']);
        return response()->json([
            'success' => true,
            'message' => 'Get All Service Category',
            'data' =>[
                'service_category' => $service_category,
                'total' => $getservicecategory['total'],
            ] ,
        ]);
    }

    public function getServiceCategoryAssoC()
    {
        //RETURN ONLY ID AND NAME
        $getServiceCategory=(new ServiceCategory())->getServiceCategory();
        return response()->json([
            'success' => true,
            'message' => 'Get All Service Category',
            'data' => $getServiceCategory,
        ]);



    }

    public function getServiceCategoryList($store_id){
       // dd($store_id);
        $getServiceCategoryList=(new ServiceCategory())->getServiceCategoryList($store_id);

        $formattedData = ServiceCategoryListResource::collection($getServiceCategoryList);
        return response()->json([
            'success' => true,
            'message' => 'Service Category List',
            'data' => [
                'ServiceCategoryList' => $formattedData,
            ]
        ],200);

    }

    public function show($store_id, $serviceCategory_id){
        $getServiceCategoryListByServiceCategory=(new ServiceCategory())->getServiceCategoryListByServiceCategory($store_id,$serviceCategory_id);
       // dd($getServiceCategoryListByServiceCategory);
        return response()->json([
            'success' => true,
            'message' => 'Get All Service Category',
            'data' =>  $getServiceCategoryListByServiceCategory,
        ]);

    }

    public function getServiceCategoryWithCategory()
    {
        // fetch service category with category
        $serviceCategoryWithCategory=(new ServiceCategory())->serviceCategoryWithCategory();
     //  dd($serviceCategoryWithCategory);

        $formattedData = ServiceCategoryWithCategoryResource::collection($serviceCategoryWithCategory);
        return response()->json([
            'success' => true,
            'message' => 'Service Category With Category',
            'data' => [
                'serviceCategoryWithCategory' => $formattedData,
            ]
        ], 200);

//        $formattedData = ServiceCategoryWithCategoryResource::collection($serviceCategoryWithCategory);

    }



    /**
     * Show the form for creating a new resource.
     */
    public function store(StoreServiceCategoryRequest $request, $store_id)
    {
       // return $store_id;
        try {
            $serviceCategory = (new ServiceCategory())->saveServiceCategory($request, $store_id);
            $success = true;
            $message = 'Service Category Submitted';
        } catch (\Throwable $throwable) {
            $success = false;
            $message = 'Failed! ' . $throwable->getMessage();
        }
        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$store_id, ServiceCategory $service_category)
    {

        //dd($store_id,$service_category);
        try {
            (new ServiceCategory())->updateServiceCategory($request,$store_id,$service_category);
            $success = true;
            $message = 'Service Category updated successfully';
        } catch (\Throwable $throwable) {
            $success = false;
            $message = 'Failed! ' . $throwable->getMessage();
        }
        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($store_id,ServiceCategory $service_category)
    {
       // dd($store_id);
        try {
            (new ServiceCategory())->deleteServiceCategory($service_category);
            $success = true;
            $message = 'Deleted Successfully';
        } catch (\Throwable $throwable) {
            $success = false;
            $message = 'Failed! ' . $throwable;
        }
        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }
}
