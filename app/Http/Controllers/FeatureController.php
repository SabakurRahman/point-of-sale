<?php

namespace App\Http\Controllers;

use App\Managers\CommonResponseManager;
use App\Models\Feature;
use Illuminate\Http\Request;

class FeatureController extends Controller
{

    private Feature $feature;
    private CommonResponseManager $commonResponse;

    public function __construct()
    {
        $this->commonResponse = new CommonResponseManager();
        $this->feature        = new Feature();
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        try {
            $features = $this->feature->getFeatures();
            $this->commonResponse->success = true;
            $this->commonResponse->data = $features;
            $this->commonResponse->message = 'Feature List';
            $this->commonResponse->commonApiResponse();
            return $this->commonResponse->response;

        }
        catch ( \Exception $e){
            $this->commonResponse->success = false;
            $this->commonResponse->message = $e->getMessage();
            $this->commonResponse->commonApiResponse();
            return $this->commonResponse->response;
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        try {
            $feature = $this->feature->createFeature($request);
            $this->commonResponse->success = true;
            $this->commonResponse->data = $feature;
            $this->commonResponse->message = 'Feature Created';
            $this->commonResponse->commonApiResponse();
            return $this->commonResponse->response;

        }
        catch ( \Exception $e){
            $this->commonResponse->success = false;
            $this->commonResponse->message = $e->getMessage();
            $this->commonResponse->commonApiResponse();
            return $this->commonResponse->response;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Feature $feature)
    {
        //
        try {
            $this->commonResponse->success = true;
            $this->commonResponse->data = $feature;
            $this->commonResponse->message = 'Feature Edit';
            $this->commonResponse->commonApiResponse();
            return $this->commonResponse->response;

        }
        catch (\Exception $e){
            $this->commonResponse->success = false;
            $this->commonResponse->message = $e->getMessage();
            $this->commonResponse->commonApiResponse();
            return $this->commonResponse->response;
        }

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Feature $feature)
    {
        //

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Feature $feature)
    {
        //
        try {
            $feature->updateFeature($request,$feature);
            $this->commonResponse->success = true;
            $this->commonResponse->data = $feature;
            $this->commonResponse->message = 'Feature Updated';
            $this->commonResponse->commonApiResponse();
            return $this->commonResponse->response;

        }
        catch (\Exception $e){
            $this->commonResponse->success = false;
            $this->commonResponse->message = $e->getMessage();
            $this->commonResponse->commonApiResponse();
            return $this->commonResponse->response;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Feature $feature)
    {
        //
        try {
            $feature->delete();
            $this->commonResponse->success = true;
            $this->commonResponse->data = $feature;
            $this->commonResponse->message = 'Feature Deleted';
            $this->commonResponse->commonApiResponse();
            return $this->commonResponse->response;

        }
        catch (\Exception $e){
            $this->commonResponse->success = false;
            $this->commonResponse->message = $e->getMessage();
            $this->commonResponse->commonApiResponse();
            return $this->commonResponse->response;
        }
    }

    public function rearrangeFeature(Request $request)
    {

        try {
            $this->feature->rearrange($request);
            $this->commonResponse->success = true;
            $this->commonResponse->data = $this->feature->getFeatures();
            $this->commonResponse->message = 'Feature Rearranged';
            $this->commonResponse->commonApiResponse();
            return $this->commonResponse->response;

        }
        catch (\Exception $e){
            $this->commonResponse->success = false;
            $this->commonResponse->message = $e->getMessage();
            $this->commonResponse->commonApiResponse();
            return $this->commonResponse->response;
        }

    }
}
