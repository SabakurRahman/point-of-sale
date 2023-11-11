<?php

namespace App\Http\Controllers;

use App\Managers\CommonResponseManager;
use App\Models\Package;
use App\Http\Requests\StorePackageRequest;
use App\Http\Requests\UpdatePackageRequest;


class PackageController extends Controller
{

    private Package $package;
    private CommonResponseManager $commonResponse;
    public function __construct()
    {
        $this->commonResponse = new CommonResponseManager();
        $this->Package        = new Package();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try {
            $packages = $this->Package->with('features')->get();
            $this->commonResponse->success = true;
            $this->commonResponse->data = $packages;
            $this->commonResponse->message = 'Package List';
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
    public function store(StorePackageRequest $request)
    {
        //
        try {

            $userPackage = $this->Package->createUserPackage($request);
            $userPackage->features()->sync($request->features);
            $this->commonResponse->success = true;
            $this->commonResponse->data = $userPackage;
            $this->commonResponse->message = 'Package Create successfully';
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
     * Display the specified resource.
     */
    public function show(Package $package)
    {
        //
        try {
            $this->commonResponse->success = true;
            $this->commonResponse->data = $package;
            $this->commonResponse->message = 'Package Edit';
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
    public function edit(Package $package)
    {

        //

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePackageRequest $request, Package $package)
    {
        //
        try {
            $package = $this->Package->updateUserPackage($request, $package);
            $package->features()->sync($request->features);
            $this->commonResponse->success = true;
            $this->commonResponse->data = $package;
            $this->commonResponse->message = 'Package Update successfully';
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
    public function destroy(Package $package)
    {
        //
        try {
            $package->delete();
            $package->features()->detach();
            $this->commonResponse->success = true;
            $this->commonResponse->data = $package;
            $this->commonResponse->message = 'Package Delete successfully';
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
