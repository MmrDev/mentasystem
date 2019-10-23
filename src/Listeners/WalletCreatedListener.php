<?php

namespace Mentasystem\Wallet\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mentasystem\Wallet\repo\AccountDB;
use Mentasystem\Wallet\repo\AccountTypeDB;
use Mentasystem\Wallet\repo\CreditDB;

class WalletCreatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param $event
     * @return mixed
     * @throws \Exception
     */
    public function handle($event)
    {
        dd("wallet created listener is run");
        $accountDB = new AccountDB();
        $accountTypeDB = new AccountTypeDB();
        $creditDB = new CreditDB();
        $data = $event->data;
        try {
            \DB::beginTransaction();

            //create account type for treasury
            $accountTypeData = [
                "type" => "treasury",
                "wallet_id" => $event->wallet->id,
                "title" => isset($data["title"]) ? $data["title"] : null,
                "subtitle" => isset($data["subtitle"]) ? $data["subtitle"] : null,
                "description" => isset($data["description"]) ? $data["description"] : null,
                "balance_type" => "ziro",
                "min_account_amount" => 0,
                "max_account_amount" => 0,
                "min_transaction_amount" => 0,
                "max_transaction_amount" => 0,
                "legal" => false,
                "interest_rate" => 1,
                "interest_period" => 1,
                "revoked" => false,
            ];
            $instanceTypeAccount = $accountTypeDB->create($accountTypeData);

            //create account for treasury
            $accountData = [
                "account_type_id" => $instanceTypeAccount->id,
                "user_id" => null,
                "min_transaction" => 0,
                "revoked" => false,
            ];
            $instanceAccount = $accountDB->create($accountData);

            //treasury account credit
            $creditData = [
                "account_id" => $instanceAccount->id,
                "wallet_id" => $event->wallet->id,
                "treasury_id" => 0,
                "club_id" => isset($data["club_id"]) ? $data["club_id"] : 0,
                "amount" => 0,
                "usable_at" => null,
                "expired_at" => null,
                "revoked" => false,
            ];
            $creditDB->create($creditData);

            \DB::commit();
            return $event->wallet;

        } catch (\Exception $e) {
            \DB::rollBack();
            throw new \Exception("some thing went wrong{$e->getMessage()}");
        }
    }

}
