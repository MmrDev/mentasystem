<?php

namespace Modules\Wallet\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Club\repo\ClubDB;
use Modules\Club\Transformers\CampaignResourceCollection;
use Modules\Wallet\Entities\Account;
use Modules\Wallet\Entities\Campaign;
use Modules\Wallet\repo\AccountDB;
use Modules\Wallet\repo\AccountTypeDB;
use Modules\Wallet\repo\CampaignDB;
use Modules\Wallet\repo\CreditDB;

class CampaignController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $limit = \request("limit") ?? 5;
        $page = \request("page") ?? 1;
        $clubDB = new ClubDB();

        $loginUserId = \Auth::user()->id;
        if ($loginUserId) {
            $userClub = $clubDB->findClubWithUserId($loginUserId);
            $clubCampaigns = $userClub[0]->campaigns;
            $result = [];
            foreach ($clubCampaigns as $clubCampaign) {
                $a = Account::find($clubCampaign->account_id);
                $b = $a->accountType()->first();
                $clubCampaign->type = $b->type;
                $clubCampaign->wallet_id = $b->wallet_id;
                $clubCampaign->title = $b->title;
                $result[] = $clubCampaign;
            }
            $resource = collect($result)->paginate($limit, $page);
            return \response()
                ->json([
                    "message" => "your data",
                    "data" => $resource
                ], 200);
        }

        return \response()
            ->json([
                "message" => "some thing went wrong",
                "error" => "you dont have any club or your arent login"
            ], 400);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $campaignDB = new CampaignDB();
        $accountTypeDB = new AccountTypeDB();
        $accountDB = new AccountDB();
        $creditDB = new CreditDB();

        try {
            \DB::beginTransaction();
            //create account_type
            $accountTypeInstance = $accountTypeDB->create($data);
            $data["account_type_id"] = $accountTypeInstance->id;
            $data["treasury_id"] = $data["wallet_id"];

            //create account
            $accountInstance = $accountDB->create($data);
            $data["account_id"] = $accountInstance->id;
            $data["amount"] = $data["budget"];

            //create credit
            $creditInstance = $creditDB->create($data);

            //create campaign
            $campaignInstance = $campaignDB->create($data);

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            return \response()
                ->json([
                    "message" => "some things went wrong",
                    "error" => "campaign is not created"
                ], 400);
        }

        //if successfully create campaign
        return \response()
            ->json([
                "message" => "campaign is successfully created",
                "data" => $campaignInstance
            ], 200);
    }

    /**
     * @param $id
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
