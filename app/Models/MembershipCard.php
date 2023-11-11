<?php

namespace App\Models;

use Illuminate\Database\Eloquent\HigherOrderBuilderProxy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class MembershipCard extends Model
{
    use HasFactory;
    protected $fillable = ['card_no', 'membership_card_type_id', 'status', 'user_id', 'store_id'];

    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 0;
    public const STATUS_LIST = [
        self::STATUS_ACTIVE   => 'Active',
        self::STATUS_INACTIVE => 'Inactive',
    ];

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
            'card_no' => $request->input('card_no'),
            'membership_card_type_id'       => $request->input('membership_card_type_id'),
            'status'         => $request->input('status'),
            'store_id'       => $store_id,
            'user_id'        => Auth::user()->id,
        ];
    }

    public function getAllMembershipCardByStore(Request $request, $store_id)
    {
        $paginate = $request->input('paginate') ?? 10;
        $query = self::query();
        if ($request->has('search') && !empty($request->input('search'))){
            $query->where('card_no', 'like', '%'.$request->input('search').'%');
        }
        return $query->where('store_id', $store_id)->with(['user', 'membership_card_type'])->paginate($paginate);
    }

    /**
     * @return BelongsTo
     */
    final public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    final public function membership_card_type(): BelongsTo
    {
        return $this->belongsTo(MembershipCardType::class);
    }

    /**
     * @param int $card_no
     * @param int $store_id
     * @return HigherOrderBuilderProxy|null
     */
    final public function getMembershipCardByCardNumber(int $card_no, int $store_id)
    {
        $membership_card_id = null;
        $card = self::query()->where([
           'card_no' => $card_no,
           'store_id' => $store_id
        ])->first();
        if ($card){
            $membership_card_id = $card->id;
        }
        return $membership_card_id;
    }


}
