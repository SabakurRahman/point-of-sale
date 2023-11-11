<?php

namespace App\Http\Controllers;

use App\Managers\CommonResponseManager;
use App\Models\Expense;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Package;
use Illuminate\Support\Facades\Log;

class ExpenseController extends Controller
{

    private Expense $expense;
    private CommonResponseManager $commonResponse;
    public function __construct()
    {
        $this->commonResponse = new CommonResponseManager();
        $this->expense        = new Expense();
    }


    /**
     * Display a listing of the resource.
     */
    public function index($store_id)
    {
        //
        try {
            $expenses = $this->expense->with('user')->get();
            $this->commonResponse->success = true;
            $this->commonResponse->data = $expenses;
            $this->commonResponse->message = 'Expense List';
            $this->commonResponse->commonApiResponse();
            return $this->commonResponse->response;

        }
        catch ( \Exception $e){
            Log::error($e->getMessage());
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
    public function store(StoreExpenseRequest $request, $store_id)
    {
        //
        try {
            $expense = $this->expense->createExpense($request, $store_id);
            $this->commonResponse->success = true;
            $this->commonResponse->data = $expense;
            $this->commonResponse->message = 'Expense Created';
            $this->commonResponse->commonApiResponse();
            return $this->commonResponse->response;

        }
        catch ( \Exception $e){
            Log::error($e->getMessage());
            $this->commonResponse->success = false;
            $this->commonResponse->message = $e->getMessage();
            $this->commonResponse->commonApiResponse();
            return $this->commonResponse->response;
        }

    }

    /**
     * Display the specified resource.
     */
    public function show($store_id,Expense $expense)
    {
        //
        try {
            $this->commonResponse->success = true;
            $this->commonResponse->data = $expense;
            $this->commonResponse->message = 'Expense Details';
            $this->commonResponse->commonApiResponse();
            return $this->commonResponse->response;

        }
        catch ( \Exception $e){
            Log::error($e->getMessage());
            $this->commonResponse->success = false;
            $this->commonResponse->message = $e->getMessage();
            $this->commonResponse->commonApiResponse();
            return $this->commonResponse->response;
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExpenseRequest $request,$store_id, Expense $expense)
    {
        //
        try {
            $expense = $this->expense->updateExpense($request, $store_id,$expense);
            $this->commonResponse->success = true;
            $this->commonResponse->data = $expense;
            $this->commonResponse->message = 'Expense Update successfully';
            $this->commonResponse->commonApiResponse();
            return $this->commonResponse->response;

        }
        catch ( \Exception $e){
            Log::error($e->getMessage());
            $this->commonResponse->success = false;
            $this->commonResponse->message = $e->getMessage();
            $this->commonResponse->commonApiResponse();
            return $this->commonResponse->response;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($store_id,Expense $expense)
    {
        try {
            $expense->delete();
            $this->commonResponse->success = true;
            $this->commonResponse->data = $expense;
            $this->commonResponse->message = 'Expense Delete successfully';
            $this->commonResponse->commonApiResponse();
            return $this->commonResponse->response;

        }
        catch ( \Exception $e){
            Log::error($e->getMessage());
            $this->commonResponse->success = false;
            $this->commonResponse->message = $e->getMessage();
            $this->commonResponse->commonApiResponse();
            return $this->commonResponse->response;
        }
    }

}
