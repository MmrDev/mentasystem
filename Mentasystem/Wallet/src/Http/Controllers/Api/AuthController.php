<?php

namespace Modules\Wallet\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Wallet\Entities\Account;
use Modules\Wallet\Events\accountRegisterEvent;
use Modules\Wallet\Http\Requests\createAccountRequest;
use Modules\Wallet\Http\Requests\loginRequest;
use Modules\Wallet\Http\Requests\MobileConfirmRequest;
use Modules\Wallet\repo\AccountDB;
use Modules\Wallet\repo\CreditDB;
use Modules\Wallet\repo\WalletDB;

class AuthController extends Controller
{
    /**
     * @param createAccountRequest $request
     * @param AccountDB $accountDB
     * @param CreditDB $creditDB
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(createAccountRequest $request, AccountDB $accountDB, CreditDB $creditDB, WalletDB $walletDB)
    {
        //register user account into database
        $data = $accountDB->convertRequestToArray($request);
        $account = $accountDB->create($data);

        //sync create user with club
        $account->Clubs()->sync([$data["club_id"]]);

        //check for successfully register
        if (!$account) {
            return \response()
                ->json([
                    'message' => __("messages.account_register_fail"),
                    "error" => __("messages.account_register_fail")
                ], 409);
        }

        //attempt user
        $this->getAttempt($request);

        //create token
        $token = Auth::user()
            ->createToken(
                "loyalty",
                ["admins", "members"]
            );

        //create random sms code and save that into redis database
        $smsCode = rand(1111, 9999);
        \Redis::set("sms_code", $smsCode);

        //sms code put into queue for send with sms server
        event(new accountRegisterEvent($account, $smsCode));

        //create account credit
        $creditDB->insert([
            "account_id" => $account->id,
            "amount" => 0,
            "treasury" => 0
        ]);

        //return response after register account
        unset($account->password);
        unset($account->settings);
        return \response()
            ->json([
                    'message' => __("messages.create_account_success"),
                    'accessToken' => $token->accessToken,
                    'data' => $account,
                ]
                , 200);
    }


    public function mobileConfirm(MobileConfirmRequest $request)
    {
        if ($request->input("sms_code") == \Redis::get("sms_code")) {
            Account::where("mobile", $request->input("mobile"))
                ->update(["mobile_verified_at" => Carbon::now()]);

            return \response()
                ->json([
                    "message" => __("message.success_confirm")
                ], 200);
        }
        return \response()
            ->json([
                "message" => __("messages.error_confirm_message"),
                "error" => __("messages.error_confirm")
            ], 403);
    }

    /**
     * try to login attempt .
     * @param loginRequest $request
     * @return mixed
     */
    public function login(loginRequest $request)
    {
        if ($this->getAttempt($request)) {
            $token = Auth::user()->createToken("loyalty");
            return \response()
                ->json([
                    "message" => __("messages.login_success"),
                    "accessToken" => $token->accessToken
                ], 200);
        }
        return \response()
            ->json([
                "message" => 'some thing went wrong',
                "error" => __("messages.login_error")
            ], 403);
    }


    /**
     * @param mixed $request
     * @return bool
     */
    private function getAttempt($request): bool
    {
        return Auth::attempt([
            'email' => $request->input("email"),
            'password' => $request->input("password")
        ]);
    }
}
