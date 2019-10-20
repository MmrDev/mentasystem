<?php

namespace Modules\Wallet\Http\Controllers\Api;

use App\Http\Resources\UserResource;
use App\repo\UserDB;
use App\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Account\Entities\Account;
use Modules\Wallet\Entities\Order;
use Modules\Wallet\repo\OrderDB;
use Modules\Wallet\repo\ProductOrderDB;
use Modules\Wallet\repo\TransactionDB;
use Modules\Wallet\Transformers\ProductOrderResource;

/**
 * @property Order refund
 */
class OrderController extends Controller
{
    private $refund;

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $limit = \request()->has("limit") ? \request()->input("limit") : 5;
        $page = \request()->has("page") ? \request()->input("page") : 1;
        $from = \request()->has("from") ? \request()->input("from") : null;
        $to = \request()->has("to") ? \request()->input("to") : null;
        $productOrderDB = new ProductOrderDB();

        //get all orders by pagination
        $productOrders = $productOrderDB->list($from, $to);

        //push list of order into resource collection
        $resource = ProductOrderResource::collection($productOrders);

        $resource = $resource->paginate($limit, $page);

        if (!$resource) {
            return response()
                ->json([
                    "message" => "some things went wrong",
                    "error" => "your collection is empty"
                ], 400);
        }
        return response()
            ->json([
                "message" => "user all orders",
                "data" => $resource
            ], 200);
    }

    /**
     * @param Request $request
     * @param OrderDB $orderDB
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store(Request $request, OrderDB $orderDB)
    {
        $userDB = resolve(UserDB::class);
        $data = $request->all();
        //get user from_user id
        $from_user_instance = $userDB->find($data["from_user_id"]);
        if ((!$from_user_instance) || ($from_user_instance->type == "customer")) {
            return \response()
                ->json([
                    "message" => "this merchant is not exist",
                    "error" => "check from user id"
                ], 400);
        }

        //get user to_user id from login user
        $to_user_instance = \Auth::user();
        if (!($to_user_instance instanceof User)) {
            return \response()
                ->json([
                    "message" => "you are not login",
                    "error" => "check login"
                ], 400);
        }

        $instance = $orderDB->create($data);
        if ($instance) {
            return $instance;
        }
        return response()
            ->json([
                "message" => "we can not create order",
                "error" => "some things went wrong"
            ], 400);
    }

    /**
     * @param $mobile
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($mobile)
    {
        //get user with him orders
        $userDB = new UserDB();
        $page = \request()->has("page") ? \request("page") : 1;
        $limit = \request()->has("limit") ? \request("limit") : 10;
        $from = \request()->has("from") ? \request("from") : null;
        $to = \request()->has("to") ? \request("to") : null;
        $day = \request()->has("day") ? \request("day") : null;
        $week = \request()->has("week") ? \request("week") : null;
        $month = \request()->has("month") ? \request("month") : null;

        $resource = $userDB->getUserProductOrder($mobile, $from, $to, $day, $week, $month);
        if (empty($resource)) {
            return response()
                ->json([
                    "message" => "some things went wrong",
                    "error" => "there is no order",
                ], 400);
        }

        //convert resource data to pagination
        $response = $resource->paginate($limit, $page);
        return response()
            ->json([
                "message" => "user orders information",
                "data" => $response
            ], 200);
    }

    /**
     * @param Request $request
     * @param $id
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        Order::where("id", $id)->delete();
        return \response()
            ->json([
                null
            ], 204);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function refund(Request $request)
    {
        $order = Order::where(["id" => $request->order_id, "refund" => false])->with("transactions")->first();
        if (!($order instanceof Order)) {
            return \response()
                ->json([
                    "message" => "your order is not found or refund before",
                    "error" => "not found data"
                ], 404);
        }

        /*-----------refund transaction---------------*/
        DB::transaction(function () use ($order) {
            $transaction = new TransactionDB();
            Order::where("id", $order->id)->update(["refund" => true]);

            //first transaction
            $data = [
                "order_id" => $order->id,
                "account_id" => $order->from_account_id,
                "amount" => $order->amount,
                "type" => "add",
                "reverse" => 1,
            ];
            $transaction->insert($data);

            //second transaction
            $data['account_id'] = $order->to_account_id;
            $data['type'] = "low_off";
            $transaction->insert($data);

            $from = Account::find($order->from_account_id);
            $to = Account::find($order->to_account_id);

            //update from account credit
            $old_credit = $from->credit->toArray();
            $new_treasury = ($old_credit["treasury"]) + ($order->amount);
            $from->credit->update([
                "treasury" => $new_treasury,
            ]);

            //update to account credit
            $old_credit = $to->credit->toArray();
            $old_treasury = ($old_credit["treasury"]) - ($order->amount);
            $to->credit->update([
                "treasury" => $old_treasury,
            ]);

            $this->refund = $order;
        });

        return \response()
            ->json([
                "message" => "success refund order",
                "data" => $this->refund
            ], 200);
    }


}
