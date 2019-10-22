<?php
/**
 * Created by PhpStorm.
 * User: a_nikookherad
 * Date: 9/7/19
 * Time: 12:26 PM
 */

namespace Mentasystem\Wallet\repo;

use Carbon\Carbon;
use Mentasystem\Wallet\Entities\Account;
use Mentasystem\Wallet\Entities\Order;
use Mentasystem\Wallet\Entities\Wallet;
use Mentasystem\Wallet\Events\CreatedOrderEvent;
use Mentasystem\Wallet\Transformers\OrderResource;

/**
 * Class OrderDB
 * @package Mentasystem\Wallet\repo
 */
class OrderDB
{
    /**
     * @param $limit
     * @param $user_id
     * @param $product_id
     * @param $wallet
     * @param $fromAmount
     * @param $toAmount
     * @param $from
     * @param $to
     * @param $day
     * @param $week
     * @param $month
     * @return mixed
     */
    public function list($limit, $user_id, $product_id, $wallet, $fromAmount, $toAmount, $from, $to, $day, $week, $month)
    {
        $filter = [
            ["paid_at", "!=", null]
        ];

        //product filter
        if (!is_null($product_id)) {
            foreach ($product_id as $key) {
                array_push($filter, ["goods_id", "=", $key]);
            }
        }

        //wallet filter
        if (!is_null($wallet)) {
            $treasuryAccountInstance = $this->walletToTreasuryAcc($wallet);
            array_push($filter, ["treasury_account_id", "=", $treasuryAccountInstance->id]);
        }

        //time filter
        if (isset($from)) {
            $from = date("Y-m-d" . " 00:00:00", strtotime($from));
            $to = date("Y-m-d" . " 00:00:00", strtotime($to));
            array_push($filter, ["created_at", ">=", $from], ["created_at", "<=", $to]);
        } elseif (isset($day) || isset($week) || isset($month)) {
            if (isset($day)) {
                array_push($filter, ["created_at", ">=", Carbon::now()->subDay($day)]);
            } elseif (isset($week)) {
                array_push($filter, ["created_at", ">=", Carbon::now()->subWeek($week)]);
            } else {
                array_push($filter, ["created_at", ">=", Carbon::now()->subMonth($month)]);
            }
        }

        // user filter
        if (!is_null($user_id)) {
            array_push($filter, ["from_account_id", "=", $user_id]);
//            $orWhere = count($user_id) > 1 ? [["from_account_id", "=", $user_id[1]]] : null;
        }

        // amount filter
        if (!is_null($fromAmount)) {
            array_push($filter, ["amount", ">=", $fromAmount]);
        }

        if (!is_null($toAmount)) {
            array_push($filter, ["amount", "<=", $toAmount]);
        }

        //inquiry of order
        $ordersInstance = Order::
        where($filter)
            ->with("transactions")
            ->paginate($limit);
//            ->get();

//        $ordersInstance = OrderResource::collection($ordersInstance);
        return $ordersInstance;
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function create($request)
    {
        $accountDB = new AccountDB();
        $from_user_id = $request["from_user_id"];
        $to_user_id = $request["to_user_id"];
        $amount = $request["amount"];
        $product_id = $request["product_id"];
        $walletDB = new WalletDB();

        try {
            \DB::beginTransaction();

            foreach ($amount as $item => $value) {
                //get database wallet type
                $databaseWallet = $walletDB->get($item);
                //if input wallet type is wrong
                if (!$databaseWallet instanceof Wallet) {
                    return response()
                        ->json([
                            "message" => "some things went wrong",
                            "error" => "your wallet type is not exist"
                        ], 400);
                }

                //get cost type
                $costTypeRials = ($databaseWallet->type == "rials") ? $databaseWallet->type : null;
                $costTypePoint = ($databaseWallet->type == "point") ? $databaseWallet->type : null;

                //TODO هر چه زودتر این تیکه کد داینامیک شود
                //get user (account) with cost type
                $fromUserRialAccount = $accountDB->getUserAccount($from_user_id, 1);
                $fromUserPointAccount = $accountDB->getUserAccount($from_user_id, 2);
                $toUserRialAccount = $accountDB->getUserAccount($to_user_id, 1);
                $toUserPointAccount = $accountDB->getUserAccount($to_user_id, 2);

                /*----------------------submit wallet order----------------------*/

                $total = [];
                /*------------cost type wallet_rials----------------*/
                if (!empty($costTypeRials)) {

                    $rialsAmount = $value;
                    $request["amount"] = $rialsAmount;

                    //submit rials wallet_order
                    $rialTreasuryAccountInstance = $accountDB->getTreasuryAccount($databaseWallet->id);

                    if (!($rialTreasuryAccountInstance instanceof Account)) {
                        return response()
                            ->json([
                                "message" => "some things went wrong",
                                "error" => "your rial treasury account does not exist"
                            ], 400);

                    }

                    /*-------------------- create wallet_order for rial ---------------------*/
                    //create wallet order
                    $orderInstance = Order::create([
                        "goods_id" => $product_id,
                        "from_account_id" => $fromUserRialAccount->id,
                        "to_account_id" => $toUserRialAccount->id,
                        "amount" => $rialsAmount,
                        "type" => "request",
                        "paid_at" => null,
                        "treasury_account_id" => $rialTreasuryAccountInstance->id,
                    ]);

                    //call submit transaction job
                    event(new CreatedOrderEvent($orderInstance, $request, $fromUserRialAccount, $toUserRialAccount, $rialTreasuryAccountInstance));
                }

                /*----------------cost type wallet_points----------------*/
                if (!empty($costTypePoint)) {

                    $pointsAmount = $value;
                    $request["amount"] = $pointsAmount;

                    //submit point wallet order
                    $pointTreasuryAccountInstance = $accountDB->getTreasuryAccount($databaseWallet->id);
                    if (!($pointTreasuryAccountInstance instanceof Account)) {
                        return response()
                            ->json([
                                "message" => "some things went wrong",
                                "error" => "your point treasury account does not exist"
                            ], 400);
                    }

                    /*-------------------- create order for point ---------------------*/

                    //create wallet order
                    $orderInstance = Order::create([
                        "goods_id" => $product_id,
                        "from_account_id" => $fromUserPointAccount->id,
                        "to_account_id" => $toUserPointAccount->id,
                        "amount" => $pointsAmount,
                        "type" => "request",
                        "paid_at" => null,
                        "treasury_account_id" => $pointTreasuryAccountInstance->id,
                    ]);

                    //call submit transaction job
                    event(new CreatedOrderEvent($orderInstance, $request, $fromUserPointAccount, $toUserPointAccount, $pointTreasuryAccountInstance));
                }
            }

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()
                ->json([
                    "message" => "some thing went wrong",
                    "error" => "your order is not created"
                ], 400);
        }
        return response()
            ->json([
                "message" => "order submit successfully",
                "data" => $orderInstance
            ], 200);
    }

    /**
     * @param $wallet
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|\Modules\Wallet\Entities\Account|object|null
     */
    private function walletToTreasuryAcc($wallet)
    {
        $response = \Modules\Wallet\Entities\Account::whereHas('accountType', function ($q) {
            $q->where("type", "=", "treasury");
        })->whereHas('accountType.wallet', function ($q) use ($wallet) {
            $q->where("title", "=", $wallet);
        })->first();
        return $response;
    }
}
