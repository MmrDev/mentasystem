<?php

namespace Mentasystem\Wallet\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Wallet\Entities\Account;
use Mentasystem\Wallet\repo\CreditDB;
use Mentasystem\Wallet\repo\AccountDB;
use Mentasystem\Wallet\repo\AccountTypeDB;

class AccountController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $repository = new AccountDB();
        $limit = request()->has("limit") ? request()->input("limit") : 10;
        $response = $repository->getAccounts($limit);
        return response()
            ->json([
                "message" => "account list",
                "data" => $response
            ], 200);
    }

    /**
     * @param AccountDB $repository
     * @param $id
     * @return bool|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Mentasystem\Wallet\Entities\Account|\Mentasystem\Wallet\Entities\Account[]|object|null
     */
    public function show(AccountDB $repository, $id)
    {
        $account = $repository->getAccount($id);
        return $account;
        /*        $account = $repository->getAccount($id);
                return new getAccountInfoResource($account);*/
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $accountDB = new AccountDB();
        $accountTypeDB = new AccountTypeDB();
        $creditDB = new CreditDB();
        $user_id = $request->user_id;

        try {
            \DB::beginTransaction();

            //search account type treasury and create for each of them account
            $allTreasuryAccountType = $accountTypeDB->getTreasury();

            //create account for every treasury account type
            foreach ($allTreasuryAccountType as $treasuryAccountType) {

                //get treasury account
                $treasuryAccountInstance = $accountDB->getTreasuryAccount($treasuryAccountType->wallet_id);

                //create account
                $accountData = [
                    "user_id" => $user_id,
                    "treasury_id" => $treasuryAccountInstance->id,
                    "account_type_id" => $treasuryAccountType->id,
                ];
                $accountInstance = $accountDB->create($accountData);

                //check for successfully create account
                if (!$accountInstance) {
                    return \response()
                        ->json([
                            'message' => __("messages.account_create_fail"),
                            "error" => __("messages.account_create_fail")
                        ], 409);
                }

                //create credit
                $creditData = [
                    "account_id" => $accountInstance->id,
                    "treasury_id" => $treasuryAccountInstance->id,
                    "amount" => 0,
                    "usable_at" => null,
                    "expired_at" => null,
                    "revoked" => false,
                ];
                $creditDB->create($creditData);
            }

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
        }

        //success response after create account
        return response()
            ->json([
                "message" => "account successfully created"
            ], 200);
    }

    /**
     * @param Requset $request
     * @param AccountDB $repository
     * @param $id
     * @return int
     */
    public function update(Requset $request, AccountDB $repository, $id)
    {
        $data = $repository
            ->convertRequestToArray($request);
        $account = Account::where('id', $id)
            ->update($data);
        return $account;
    }

    /**
     * @param AccountDB $repository
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(AccountDB $repository, $id)
    {
        $repository->deleteAccount($id);
        return \response()
            ->json([
                'message' => __("messages.user_success_deleted")
            ], 204);
    }
}
