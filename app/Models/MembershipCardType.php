<?php

namespace App\Models;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MembershipCardType extends Model
{
    use HasFactory;

    protected $fillable = ['card_type_name', 'discount', 'status', 'user_id', 'store_id'];

    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 0;
    public const STATUS_LIST = [
        self::STATUS_ACTIVE   => 'Active',
        self::STATUS_INACTIVE => 'Inactive',
    ];

    /**
     * @param int $store_id
     * @return LengthAwarePaginator
     */
    final public function getAllMembershipCardTypeByStore(int $store_id): LengthAwarePaginator
    {
        return self::query()->where('store_id', $store_id)->with('user')->paginate(10);
    }

    /**
     * @param Request $request
     * @param int $store_id
     * @return Builder|Model
     */
    final public function storeMembershipCard(Request $request, int $store_id): Builder|Model
    {
        return self::query()->create($this->prepareData($request, $store_id));
    }

    /**
     * @param Request $request
     * @param int $store_id
     * @return array
     */

    private function prepareData(Request $request, int $store_id): array
    {
        return [
            'card_type_name' => $request->input('card_type_name'),
            'discount'       => $request->input('discount'),
            'status'         => $request->input('status'),
            'store_id'       => $store_id,
            'user_id'        => Auth::user()->id,
        ];
    }

    /**
     * @return BelongsTo
     */
    final public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param int $store_id
     * @return Builder|Collection
     */
    final public function getMembershipCardListNameIdByStore(int $store_id): Builder|Collection
    {
        return self::query()->where('store_id', $store_id)->where('status', self::STATUS_ACTIVE)->select('id', 'card_type_name')->get();
    }
}
