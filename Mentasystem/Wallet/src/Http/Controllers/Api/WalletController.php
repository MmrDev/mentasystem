<?php

namespace Modules\Wallet\Http\Controllers\Api;

use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Wallet\Entities\Account;
use Modules\Wallet\Entities\AccountType;
use Modules\Wallet\Entities\Credit;
use Modules\Wallet\Events\WalletCreatedEvent;
use Modules\Wallet\Events\WalletDeletedEvent;
use Modules\Wallet\repo\WalletDB;
use Modules\Wallet\Entities\Wallet;

class WalletController extends Controller
{
    /**
     *
     */
    public function index()
    {

    }

    /**
     * @param Request $request
     * @param WalletDB $walletDB
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */

    public function create(Request $r, WalletDB $walletDB)
    {
        $data = $r->all();
        try {
            \DB::beginTransaction();

            //create wallet (wallet type)
            $walletInstance = $walletDB->create($data);

            //after create wallet event(create two treasury account)
            $result = event(new WalletCreatedEvent($walletInstance, $data));

            \DB::commit();
            return Response()->json([
                'message' => 'wallet created successfully',
                'data' => $result
            ], 200);
        } catch (\Exception $e) {
            \DB::rollBack();
            return \response()
                ->json([
                    "message" => "some things went wrong",
                    "error" => "{$e->getMessage()}"
                ], 400);
        }
    }

    public function store(Request $request, WalletDB $walletDB)
    {
        $data = $request->all();
        try {
            \DB::beginTransaction();

            //create wallet (wallet type)
            $walletInstance = $walletDB->create($data);

            //after create wallet event(create two treasury account)
            event(new WalletCreatedEvent($walletInstance, $data));

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            return \response()
                ->json([
                    "message" => "some things went wrong",
                    "error" => "{$e->getMessage()}"
                ], 400);
        }

        //check for create wallet
        if ($walletInstance) {
            return \response()
                ->json([
                    "message" => "wallet successfully created"
                ], 200);
        }
    }

    /**
     * @param $id
     */
    public function list(Request $r)
    {
        return Wallet::where($r->filters[0][0], $r->filters[0][1], $r->filters[0][2])
            ->paginate($r->input("page_limit"), ["*"], 'page', $r->input("page_number"));
    }

    /**
     * @param $id
     */
    public function show($id)
    {
        return Wallet::where("id", $id)->get();
    }

    /**
     * @param Request $request
     * @param $id
     */
    public function update(Request $r, $id, WalletDB $walletDB)
    {
        $data = $r->all();
        $walletInstance = $walletDB->find($id);
        if (is_null($walletInstance)) {
            return response()
                ->json('id (' . $id . ') dose not exist', 400);
        }
        if ($walletDB->update($id, $data)) {
            return response()->json($walletDB->find($id), 201);
        } else {
            return Response()
                ->json([
                    'message' => "wallet can't be updated, update it again"
                ], 400);
        }
    }

    /**
     * @param $id
     */
    public function destroy($id, WalletDB $walletDB)
    {
        event(new WalletDeletedEvent($id));
        if (is_null($walletDB->find($id))) return response()->json(['id (' . $id . ') dose not exist']);
        try {
            \DB::beginTransaction();
            //delete wallet (wallet type)
            $walletDB->delete($id);


            //after delete wallet delete [accType, acc, credit]
            $accTypeId = AccountType::select("id")->where("wallet_id", $id)->get()->toArray()[0]["id"];
            AccountType::where("wallet_id", $id)->delete();
            Account::where("account_type_id", $accTypeId)->delete();
            credit::where("account_id", $accTypeId)->delete();

            \DB::commit();
            return response()
                ->json('', 204);
        } catch (\Exception $e) {
            \DB::rollBack();
            return \response()
                ->json([
                    "message" => "some things went wrong",
                    "error" => "{$e->getMessage()}"
                ], 400);
        }
    }

    public function walletCr6663edit(Request $r)
    {
        $idUser = auth()->user()["id"];
        $account = Account::where('user_id', $idUser)
            ->with(['credits', 'accountType'])->whereHas('accountType', function ($q) {
                $q->with("wallet");
            })->get();
        $walletTitle = [];
        $creditInfo = [];
        foreach ($account->toArray() as $key) {
            $credit = $key["credits"][0];
            $walletTitle = Wallet::where("id", $key["account_type"]["id"])->get()->toArray()[0]["title"];
            $Arr = ["id" => $credit["id"],
                "club_id" => $credit["club_id"],
                "amount" => $credit["amount"],
                "type" => $walletTitle,
                "expired_at" => $credit["expired_at"],
                "usable_at" => $credit["usable_at"]];

            array_push($creditInfo, $Arr);
        };

        $walletTitle = $account[0]->accountType->wallet->toArray()["title"];
        return Response()->json([
            $creditInfo
        ], 200);
    }
}
