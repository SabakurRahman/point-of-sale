<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\CustomerDetailsResource;
use App\Managers\CommonResponseManager;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class CustomerController extends Controller
{
    private Customer $customer;
    private CommonResponseManager $commonResponse;
    public function __construct()
    {
        $this->commonResponse = new CommonResponseManager();
        $this->customer = new Customer();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(int $store_id, StoreCustomerRequest $request)
    {
        try {
            $customer = $this->customer->getCustomerByPhone($request->input('customer_phone'), $store_id);

            if (!$customer){
                $customer = $this->customer->storeCustomer($request, $store_id);
            }elseif (empty($customer->membership_card_id) && $request->has('customer_card')){
                $this->customer->updateCustomer($request, $customer);

            }
                if ($request->has('customer_name') && $request->input('customer_name') !== $customer->name) {
                    $updateData['name'] = $request->input('customer_name');
                }

                if ($request->has('customer_address') && $request->input('customer_address') !== $customer->address) {
                    $updateData['address'] = $request->input('customer_address');
                }
                if (!empty($updateData)) {
                    $customer->update($updateData);
                }


            $customer->load(['membership_card', 'membership_card.membership_card_type','order']);

            $this->commonResponse->success = true;
            $this->commonResponse->data = new CustomerDetailsResource($customer);
            $this->commonResponse->message = 'Customer added successfully';
        }catch (Throwable $throwable){
            $this->commonResponse->success = false;
            $this->commonResponse->message = 'Failed! ' . $throwable->getMessage();
        }
        $this->commonResponse->commonApiResponse();
        return $this->commonResponse->response;
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }


    public function getCustomerDetails(int $store_id, Request $request)
    {
        $customer_data = null;
        if ($request->has('column')){
            if ($request->input('column') == 'customer_phone'){
             $customer_data = $this->customer->getCustomerByPhone($request->input('value'), $store_id);
            }elseif ($request->input('column') == 'customer_card'){
              $customer_data = $this->customer->getCustomerByMembershipCard($request->input('value'), $store_id);
            }
        }


        if ($customer_data){
            $customer_data = new CustomerDetailsResource($customer_data);
        }
        $this->commonResponse->success = true;
        $this->commonResponse->data = $customer_data;
        $this->commonResponse->message = 'Customer data fetched successfully';
        $this->commonResponse->commonApiResponse();
        return $this->commonResponse->response;
    }

}
