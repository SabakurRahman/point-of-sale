<?php

namespace App\Http\Controllers;

use App\Http\Resources\MembershipCardTypeListResource;
use App\Managers\CommonResponseManager;
use App\Models\MembershipCardType;
use App\Http\Requests\StoreMembershipCardTypeRequest;
use App\Http\Requests\UpdateMembershipCardTypeRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class MembershipCardTypeController extends Controller
{
    private CommonResponseManager $commonResponse;
    private MembershipCardType $membershipCardType;

    public function __construct()
    {
        $this->commonResponse     = new CommonResponseManager();
        $this->membershipCardType = new MembershipCardType();
    }

    /**
     * @param Request $request
     * @param int $store_id
     * @return JsonResponse
     */
    final public function index(Request $request, int $store_id): JsonResponse
    {

        $membership_card_type_data     = $this->membershipCardType->getAllMembershipCardTypeByStore($store_id);
        $this->commonResponse->data    = MembershipCardTypeListResource::collection($membership_card_type_data);
        $this->commonResponse->success = true;
        $this->commonResponse->meta    = [
            'total'        => $membership_card_type_data->total(),
            'per_page'     => $membership_card_type_data->perPage(),
            'total_pages'  => $membership_card_type_data->lastPage(),
            'current_page' => $membership_card_type_data->currentPage(),
            'last_page'    => $membership_card_type_data->lastPage(),
            'from'         => $membership_card_type_data->firstItem(),
            'to'           => $membership_card_type_data->lastItem(),
        ];
        $this->commonResponse->links   = [
            'first_page_url' => $membership_card_type_data->url(1),
            'last_page_url'  => $membership_card_type_data->url($membership_card_type_data->lastPage()),
            'next_page_url'  => $membership_card_type_data->nextPageUrl(),
            'prev_page_url'  => $membership_card_type_data->previousPageUrl(),
        ];
        $this->commonResponse->success = true;
        $this->commonResponse->commonApiResponse();
        return $this->commonResponse->response;
    }

    /**
     * @param StoreMembershipCardTypeRequest $request
     * @param int $store_id
     * @return JsonResponse
     */
    final public function store(StoreMembershipCardTypeRequest $request, int $store_id): JsonResponse
    {
        try {

            $this->commonResponse->data    = $this->membershipCardType->storeMembershipCard($request, $store_id);
            $this->commonResponse->success = true;
            $this->commonResponse->message = 'Membership card type added successfully';

        } catch (Throwable $e) {
            $this->commonResponse->success = false;
            $this->commonResponse->message = 'Failed! ' . $e->getMessage();
        }
        $this->commonResponse->commonApiResponse();
        return $this->commonResponse->response;
    }

    /**
     * @param int $store_id
     * @param MembershipCardType $membership_card_type
     * @return JsonResponse
     */
    final public function show(int $store_id, MembershipCardType $membership_card_type): JsonResponse
    {
        $this->commonResponse->data    = $membership_card_type;
        $this->commonResponse->success = true;
        $this->commonResponse->commonApiResponse();
        return $this->commonResponse->response;
    }

    /**
     * @param int $store_id
     * @param UpdateMembershipCardTypeRequest $request
     * @param MembershipCardType $membership_card_type
     * @return JsonResponse
     */
    final public function update(int $store_id, UpdateMembershipCardTypeRequest $request, MembershipCardType $membership_card_type): JsonResponse
    {
        try {
            $membership_card_type->update($request->all());
            $this->commonResponse->success = true;
            $this->commonResponse->message = 'Cart Type Updated Successfully';
        } catch (Throwable $throwable) {
            $this->commonResponse->success = false;
            $this->commonResponse->message = 'Failed! ' . $throwable->getMessage();
        }
        $this->commonResponse->commonApiResponse();
        return $this->commonResponse->response;
    }

    /**
     * @param int $store_id
     * @param MembershipCardType $membership_card_type
     * @return JsonResponse
     */
    final public function destroy(int $store_id, MembershipCardType $membership_card_type): JsonResponse
    {
        try {
            $membership_card_type->delete();
            $this->commonResponse->success = true;
            $this->commonResponse->message = 'Cart Type deleted Successfully';
        } catch (Throwable $throwable) {
            $this->commonResponse->success = false;
            $this->commonResponse->message = 'Failed! ' . $throwable->getMessage();
        }
        $this->commonResponse->commonApiResponse();
        return $this->commonResponse->response;
    }

    /**
     * @param int $store_id
     * @return JsonResponse
     */
    final public function membership_card_list(int $store_id): JsonResponse
    {
        $this->commonResponse->data = $this->membershipCardType->getMembershipCardListNameIdByStore($store_id);
        $this->commonResponse->commonApiResponse();
        return $this->commonResponse->response;
    }
}
