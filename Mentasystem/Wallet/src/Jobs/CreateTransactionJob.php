<?php

namespace Mentasystem\Wallet\Jobs;

use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Mentasystem\Wallet\repo\AccountDB;
use Mentasystem\Wallet\repo\TransactionDB;
use mysql_xdevapi\Exception;

class CreateTransactionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $order;
    private $request;
    private $fromAccountInstance;
    private $toAccountInstance;
    private $treasuryInstance;
    private $productOrderInstance;

    /**
     * CreateTransactionJob constructor.
     * @param $order
     * @param null $request
     * @param null $fromAccountInstance
     * @param null $toAccountInstance
     * @param null $treasuryInstance
     * @param null $productOrderInstance
     */
    public function __construct($order, $request = null, $fromAccountInstance = null, $toAccountInstance = null, $treasuryInstance = null, $productOrderInstance = null)
    {
        $this->order = $order;
        $this->request = $request;
        $this->fromAccountInstance = $fromAccountInstance;
        $this->toAccountInstance = $toAccountInstance;
        $this->treasuryInstance = $treasuryInstance;
        $this->productOrderInstance = $productOrderInstance;
    }

    /**
     * create transaction
     * @throws \Throwable
     */
    public function handle()
    {
        //initial variable
        $transaction = new TransactionDB();
        $accountDB = resolve(AccountDB::class);
        $orderInstance = $this->order;

        if (isset($this->fromAccountInstance)) {
            //create real shop order
            $request = $this->request;
            $fromAccountInstance = $this->fromAccountInstance;
            $toAccountInstance = $this->toAccountInstance;
            $treasuryAccountInstance = $this->treasuryInstance;
        } else {
            // create gift order
            $request["amount"] = $orderInstance->amount;
            $fromAccountInstance = $accountDB->get($orderInstance->from_account_id);
            $toAccountInstance = $accountDB->get($orderInstance->to_account_id);
            $treasuryAccountInstance = $accountDB->get($orderInstance->treasury_account_id);
        }

        /*-----begin transaction------*/
        try {
            DB::beginTransaction();
            /*------------ first transaction ---------*/
            $transactionData1 = [
                "from_account_id" => $fromAccountInstance->id,
                "to_account_id" => $treasuryAccountInstance->id,
                "amount" => $request["amount"],
                "order_id" => $orderInstance->id,
            ];
            $transactionInstance1 = $transaction->create($transactionData1);

            //get from_account credit
            $old_credit = DB::table("accounts")
                ->where("accounts.id", "=", $fromAccountInstance->id)
                ->join("credits", "accounts.id", "=", "credits.account_id")
                ->where("credits.treasury_id", "=", $treasuryAccountInstance->id)
                ->select("credits.amount")
                ->first()->amount;

            $new_credit = ($old_credit) - ($request["amount"]);

            //update from_account credit
            DB::table("credits")
                ->where("credits.treasury_id", "=", $treasuryAccountInstance->id)
                ->join("accounts", "credits.account_id", "=", "accounts.id")
                ->where("accounts.id", "=", $fromAccountInstance->id)
                ->update([
                    'amount' => $new_credit,
                ]);

            /*-------- second transaction -----------*/
            $transactionData2 = [
                "from_account_id" => $treasuryAccountInstance->id,
                "to_account_id" => $toAccountInstance->id,
                "amount" => $request["amount"],
                "order_id" => $orderInstance->id,
            ];
            $transactionInstance2 = $transaction->create($transactionData2);

            //get to_account credit
            $old_credit_amount = DB::table("accounts")
                ->where("accounts.id", "=", $toAccountInstance->id)
                ->join("credits", "accounts.id", "=", "credits.account_id")
                ->where("credits.treasury_id", "=", $treasuryAccountInstance->id)
                ->select("credits.amount")
                ->first()->amount;

            $new_credit_amount = ($old_credit_amount) + ($request["amount"]);

            //update to_account credit
            DB::table("credits")
                ->where("credits.treasury_id", "=", $treasuryAccountInstance->id)
                ->join("accounts", "credits.account_id", "=", "accounts.id")
                ->where("accounts.id", "=", $toAccountInstance->id)
                ->update([
                    'amount' => $new_credit_amount,
                ]);

            //save paid_at into order instance
            $orderInstance->paid_at = Carbon::now();
            $orderInstance->save();

            //save paid_at into product order instance
            if (isset($this->productOrderInstance)) {
                $productOrderInstance = $this->productOrderInstance;
                $productOrderInstance->paid_at = Carbon::now();
                $productOrderInstance->save();
            }
            /*
                        //TODO must be refactor as soon
                        if ($treasuryAccountInstance->id == 1) {
                            //save event into Event module
                            $eventData = [
                                "from" => $fromAccountInstance->id,
                                "to" => $toAccountInstance->id,
                                "amount" => $orderInstance->amount,
                                "type_id" => 1,
                                "done_at" => null,
                                "UID" => Str::uuid()->toString(),
                                "extra_value" => null,
                            ];
                            $eventInstance = $eventDB->create($eventData);

                            //get club event and verify to get some campaign
                            event(new CreateEvent($eventInstance, $orderInstance));
                        }*/

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Throw new Exception("transaction is not create");
        }

    }
}
