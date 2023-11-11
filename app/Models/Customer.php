<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address', 'status', 'phone', 'membership_card_id', 'store_id'];


    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 2;
    public const STATUS_LIST = [
        self::STATUS_ACTIVE   => 'Active',
        self::STATUS_INACTIVE => 'Inactive',
    ];


    /**
     * @param Request $request
     * @param int $store_id
     * @return Builder|Model
     */
    final public function storeCustomer(Request $request, int $store_id): Builder|Model
    {
        return self::query()->create($this->prepareData($request, $store_id));
    }

    private function prepareData(Request $request, int $store_id): array
    {
        $membership_card_id = null;
        if ($request->has('customer_card')) {
            if (strlen($request->input('customer_card')) == 16) {
                $membership_card_id = (new MembershipCard())->getMembershipCardByCardNumber($request->input('customer_card'), $store_id);
            } else {
                $membership_card_id = $request->input('customer_card');
            }
        }
        return [
            'phone'              => $request->input('customer_phone'),
            'name'               => $request->input('customer_name'),
            'address'            => $request->input('customer_address'),
            'membership_card_id' => $membership_card_id,
            'status'             => self::STATUS_ACTIVE,
            'store_id'           => $store_id
        ];
    }

    /**
     * @param string $phone
     * @param int $store_id
     * @return Builder|null
     */
    final public function getCustomerByPhone(string $phone, int $store_id)
    {
        return self::query()->with(['membership_card','order', 'membership_card.membership_card_type'])->where(
            [
                'phone'    => $phone,
                'store_id' => $store_id
            ]
        )->first();
    }

    public function getCustomerByMembershipCard(string $card_no, int $store_id)
    {
        $membership_card = (new MembershipCard())->getMembershipCardByCardNumber($card_no, $store_id);
        $customer        = null;
        if ($membership_card != null) {
            $customer = self::query()->with(['membership_card','order', 'membership_card.membership_card_type'])->where(
                [
                    'membership_card_id' => $membership_card,
                    'store_id'           => $store_id
                ]
            )->first();
        }
        return $customer;
    }

    public function membership_card()
    {
        return $this->belongsTo(MembershipCard::class);
    }

    /**
     * @param Request $request
     * @param Customer $customer
     * @return bool
     */
    final public function updateCustomer(Request $request, Customer $customer): bool
    {
        $membership_card_id = null;
        if ($request->has('customer_card')) {
            if (strlen($request->input('customer_card')) == 16) {
                $membership_card_id = (new MembershipCard())->getMembershipCardByCardNumber($request->input('customer_card'), $customer->store_id);
            } else {
                $membership_card_id = $request->input('customer_card');
            }
        }
        $customer_data = [
            'membership_card_id' => $membership_card_id,


        ];

        return $customer->update($customer_data);
    }

    public function order()
    {
        return $this->hasMany(Order::class);
    }
}
