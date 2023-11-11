<?php

namespace App\Http\Controllers;

use App\Http\Resources\MembershipCardListResource;
use App\Managers\CommonResponseManager;
use App\Models\MembershipCard;
use App\Http\Requests\StoreMembershipCardRequest;
use App\Http\Requests\UpdateMembershipCardRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class MembershipCardController extends Controller
{

    private CommonResponseManager $commonResponse;
    private MembershipCard $membershipCard;

    public function __construct()
    {
        $this->commonResponse = new CommonResponseManager();
        $this->membershipCard = new MembershipCard();
    }

    /**
     * @param Request $request
     * @param int $store_id
     * @return JsonResponse
     */
    final public function index(Request $request, int $store_id): JsonResponse
    {
        $membership_card               = $this->membershipCard->getAllMembershipCardByStore($request, $store_id);
        $this->commonResponse->data    = MembershipCardListResource::collection($membership_card);
        $this->commonResponse->success = true;
        $this->commonResponse->meta    = [
            'total'        => $membership_card->total(),
            'per_page'     => $membership_card->perPage(),
            'total_pages'  => $membership_card->lastPage(),
            'current_page' => $membership_card->currentPage(),
            'last_page'    => $membership_card->lastPage(),
            'from'         => $membership_card->firstItem(),
            'to'           => $membership_card->lastItem(),
        ];
        $this->commonResponse->links   = [
            'first_page_url' => $membership_card->url(1),
            'last_page_url'  => $membership_card->url($membership_card->lastPage()),
            'next_page_url'  => $membership_card->nextPageUrl(),
            'prev_page_url'  => $membership_card->previousPageUrl(),
        ];
        $this->commonResponse->success = true;
        $this->commonResponse->commonApiResponse();
        return $this->commonResponse->response;
    }


    /**
     * @param StoreMembershipCardRequest $request
     * @param int $store_id
     * @return JsonResponse
     */
    final public function store(StoreMembershipCardRequest $request, int $store_id): JsonResponse
    {
        try {
            $this->commonResponse->data    = $this->membershipCard->storeMembershipCard($request, $store_id);
            $this->commonResponse->success = true;
            $this->commonResponse->message = 'Membership card added successfully';
        } catch (Throwable $e) {
            $this->commonResponse->success = false;
            $this->commonResponse->message = 'Failed! ' . $e->getMessage();
        }
        $this->commonResponse->commonApiResponse();
        return $this->commonResponse->response;
    }

    /**
     * @param int $store_id
     * @param MembershipCard $membership_card
     * @return JsonResponse
     */
    final public function show(int $store_id, MembershipCard $membership_card): JsonResponse
    {
        $this->commonResponse->data    = $membership_card;
        $this->commonResponse->success = true;
        $this->commonResponse->commonApiResponse();
        return $this->commonResponse->response;
    }


    /**
     * @param int $store_id
     * @param UpdateMembershipCardRequest $request
     * @param MembershipCard $membership_card
     * @return JsonResponse
     */
    final public function update(int $store_id, UpdateMembershipCardRequest $request, MembershipCard $membership_card): JsonResponse
    {
        try {
            $membership_card->update($request->all());
            $this->commonResponse->success = true;
            $this->commonResponse->message = 'Cart Updated Successfully';
        } catch (Throwable $throwable) {
            $this->commonResponse->success = false;
            $this->commonResponse->message = 'Failed! ' . $throwable->getMessage();
        }
        $this->commonResponse->commonApiResponse();
        return $this->commonResponse->response;
    }

    /**
     * @param int $store_id
     * @param MembershipCard $membership_card
     * @return JsonResponse
     */
    final public function destroy(int $store_id, MembershipCard $membership_card): JsonResponse
    {
        try {
            $membership_card->delete();
            $this->commonResponse->success = true;
            $this->commonResponse->message = 'Cart deleted Successfully';
        } catch (Throwable $throwable) {
            $this->commonResponse->success = false;
            $this->commonResponse->message = 'Failed! ' . $throwable->getMessage();
        }
        $this->commonResponse->commonApiResponse();
        return $this->commonResponse->response;
    }
}
