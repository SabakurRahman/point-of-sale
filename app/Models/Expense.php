<?php

namespace App\Models;

use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Expense extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const active = 1;
    public const inactive = 2;

    public const status = [
        self::active => 'active',
        self::inactive => 'inactive',
    ];


    public function createExpense(StoreExpenseRequest $request, $store_id)
    {
        return self::query()->create($this->prepareExpense($request, $store_id));
    }

    private function prepareExpense(StoreExpenseRequest $request, $store_id)
    {
        return [
            'purpose' => $request->purpose,
            'amount'  => $request->amount,
            'date'    => $request->date,
            'user_id' => Auth::id(),
            'store_id' => $store_id,
            'status'  => $request->status ?? self::active,
        ];
    }


    public function updateExpense(UpdateExpenseRequest $request,$store_id, Expense $expense)
    {
        return $expense->update($this->updateExpenseData($request, $store_id, $expense,));

    }

    private function updateExpenseData(UpdateExpenseRequest $request,$store_id,Expense $expense)
    {
        $data = [
            'purpose' => $request->purpose ?? $expense->purpose,
            'amount'  => $request->amount ?? $expense->amount,
            'date'    => $request->date ?? $expense->date,
            'user_id' => Auth::id(),
            'store_id' => $store_id,
            'status'  => $request->status ?? $expense->status,
        ];
        return $data;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }



}
