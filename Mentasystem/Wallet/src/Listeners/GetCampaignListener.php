<?php

namespace Modules\Wallet\Listeners;

use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Wallet\Entities\Account;
use Modules\Wallet\Entities\Order;
use Modules\Wallet\Events\CreatedOrderEvent;

$className = "Modules\Wallet\Listeners\Testing";


class GetCampaignListener
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
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        $eventInstance = $event->eventInstance;
        $orderInstance = $event->orderInstance;
        $amount = $orderInstance->amount;
        $clubInstance = $event->clubInstance;

        //getter all campaign
        $campaigns = $clubInstance->campaigns;

        //call every campaign for one club
        foreach ($campaigns as $campaign) {

            //get campaign rules
            $rules = $campaign->rules;

            //call every rule for one campaign
            foreach ($rules as $rule) {
                $params = $rule->params;
                $operators = $rule->operators;
                $values = $rule->values;
                $statement = false;

                $phpEvalString = '$statement = ' . $params . $operators . $values . ';';

                //run rule from database
                eval($phpEvalString);

                //run rule
                if ($statement) {
                    //get rule process
                    $processes = $rule->processes;

                    //call every class for one rule
                    foreach ($processes as $process) {

                        //do action
                        $processClass = $process->class;
                        $processMethod = $process->methods;
                        $processParams = $process->params;
                        $methodName = $process->method;

                        $processParams = str_replace('{{amount}}', $eventInstance->amount, $processParams);

//                        foreach ($processParams as $processParam) {
//                            $process  = str_replace('{{amount}}', $event->amount,$processParam);
//                        }

                        //create class and its methods
                        $fileContent = file_get_contents(app_path("Process/ClassName.stub"));
                        $newFile = str_replace('$CLASSNAME$', $processClass, $fileContent);
                        $newFile = str_replace('$METHODNAMES$', $processMethod, $newFile);
                        file_put_contents(app_path("Process/{$processClass}.php"), $newFile);

                        //call class and its methods & params
                        $processClass = "App\\Process\\$processClass";
                        $instanceOfClass = new $processClass();

                        //call class method
//                        $instanceOfClass->$methodName($processParams);

                        //get point account type
                        $pointAccount = \DB::table("accounts")
                            ->where("accounts.id", "=", $orderInstance->from_account_id)
                            ->join("users", "accounts.user_id", "=", "users.id")
                            ->join("accounts as acc", "users.id", "=", "acc.user_id")
                            ->where("acc.treasury_id", "=", 2)
                            ->select("acc.id")
                            ->first()->id;

                        //create function from insert order
                        $orderInstance->treasury_account_id = 2;
                        $orderInstance->from_account_id = $campaign->account_id;
                        $orderInstance->to_account_id = $pointAccount;
                        $orderInstance->amount = $orderInstance->amount * (2 / 10);

                        $this->createWalletOrder($orderInstance);

                    }
                }
            }
        }
    }

    /**
     * @param $orderInstance
     */
    private function createWalletOrder($orderInstance): void
    {
        /*-------------------- create order ---------------------*/
        //create wallet order
        $orderInstance = Order::create([
            "goods_id" => null,
            "from_account_id" => $orderInstance->from_account_id,
            "to_account_id" => $orderInstance->to_account_id,
            "amount" => $orderInstance->amount,
            "treasury_account_id" => $orderInstance->treasury_account_id,
        ]);

        //call submit transaction job
        event(new CreatedOrderEvent($orderInstance));
    }
}
