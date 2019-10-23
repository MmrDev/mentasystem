<?php

namespace Modules\Wallet\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Wallet\Entities\AccountType;

class WalletDeletedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        $data = $event->id;
        try {
            \DB::beginTransaction();
//            $instanceTypeAccount = $accountTypeDB->create($accountTypeData);
//            $instanceAccount = $accountDB->create($accountData);
//            $creditDB->delete($wallet);
            AccountType::where("wallet_id", $data->id)->delete();
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            return "some thing went wrong{$e->getMessage()}";
        }
    }
}
