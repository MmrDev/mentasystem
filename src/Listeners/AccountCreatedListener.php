<?php

namespace Modules\Wallet\Listeners;

use App\Exceptions\CreateWalletException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Wallet\repo\CreditDB;

class AccountCreatedListener
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
     * @throws CreateWalletException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Throwable
     */
    public function handle($event)
    {
        $creditDB = app()->make(CreditDB::class);
        $data = $event->data;
        try {
            /*---------create wallet and credit transaction----------*/
            \DB::beginTransaction();

            //create credit
            $creditData = [
                "account_id" => $event->account->id,
                "treasury_id" => $data['treasury_id'],
                "amount" => $data["amount"] ?? 0,
//                "club_id" => $data["club_id"],
                "usable_at" => isset($data["usable_at"]) ? $data["usable_at"] : null,
                "expired_at" => isset($data["expired_at"]) ? $data["expired_at"] : null,
                "revoked" => false,
            ];
            $ins = $creditDB->create($creditData);

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            throw new CreateWalletException("some things went wrong:{$e->getMessage()}");
        }
    }
}
