<?php

namespace Modules\Wallet\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Wallet\Entities\Account;
use Modules\Wallet\Http\Requests\updateAccountRequset;
use Modules\Wallet\repo\CreditDB;
use Modules\Wallet\repo\AccountDB;

class AccountController extends Controller
{
    /**
     * Display a listing of the account.
     * @param AccountDB $repository
     * @return false|string
     */
    public function index(AccountDB $repository)
    {
        $limit = request()->has("limit") ? request()->input("limit") : 5;
        return $repository->getAccounts($limit);
    }

    /**
     * Show the specified account.
     * @param int $id
     * @param AccountDB $repository
     * @return mixed
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
     * @param AccountDB $accountDB
     * @param CreditDB $creditDB
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store(Request $request, AccountDB $accountDB, CreditDB $creditDB)
    {
        try {
            \DB::beginTransaction();
            //insert account into database
            $accountData = $request->all();
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
                "treasury_id" => 1,
                "amount" => $accountData['amount'],
                "usable_at" => null,
                "expired_at" => null,
                "revoked" => false,
            ];
            $creditDB->create($creditData);

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
     * Update the specified resource in storage.
     * @param updateAccountRequset $request
     * @param int $id
     * @param AccountDB $repository
     * @return mixed
     */
    public function update(updateAccountRequset $request, AccountDB $repository, $id)
    {
        $data = $repository
            ->convertRequestToArray($request);
        $account = Account::where('id', $id)
            ->update($data);
        return $account;
    }

    /**
     * Remove the specified account from storage.
     * @param int $id
     * @param AccountDB $repository
     * @return false|string
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
