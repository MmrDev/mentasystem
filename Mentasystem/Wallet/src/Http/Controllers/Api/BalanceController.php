<?php

namespace Modules\Wallet\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Wallet\Entities\Balance;
use Modules\Wallet\Transformers\GetBalanceResource;

class BalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return mixed
     */
    public function index()
    {
        $limit = \request()->has("limit") ? \request()->input("limit") : 5;
        $balances = Balance::paginate($limit);
        $response = GetBalanceResource::collection($balances);
        return $response;
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return false|mixed
     */
    public function store(Request $request)
    {
        //prepare data to save in database
        $data = $this->getArr($request);

        //insert data into database
        $instance = Balance::create($data);

        //response for data successfully save in database
        if ($instance instanceof Balance) {
            return \response()
                ->json([
                    "message" => __("messages.create_balance_success"),
                    "data" => $instance
                ], 200);
        }

        //response for some things went wrong and data is not save into database
        return \response()
            ->json([
                "message" => __("messages.create_balance_error_message"),
                "error" => __("messages.create_balance_error")
            ], 400);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return mixed
     */
    public function show($id)
    {
        $balance = Balance::find($id);
        $response = new GetBalanceResource($balance);
        return $response;
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return mixed
     */
    public function destroy($id)
    {
        Balance::where("id", $id)->delete();
        return \response()
            ->json([
                null
            ], 204);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getArr(Request $request): array
    {
        $data = [
            'from_account_id' => $request->has("from_account_id") ? $request->input("from_account_id") : null,
            'to_account_id' => $request->has("to_account_id") ? $request->input("to_account_id") : null,
            'good_id' => $request->has("good_id") ? $request->input("good_id") : null,
            'amount' => $request->has("amount") ? $request->input("amount") : null,
            'revoked' => $request->has("revoked") ? $request->input("revoked") : null,
            'author' => $request->has("author") ? $request->input("author") : null,
            'uuid' => $request->has("uuid") ? $request->input("uuid") : null,
            'reverse' => $request->has("reverse") ? $request->input("reverse") : null,
            'extraValue' => $request->has("extraValue") ? $request->input("extraValue") : null,
            'goodExtraValue' => $request->has("goodExtraValue") ? $request->input("goodExtraValue") : null,
            'parent_id' => $request->has("parent_id") ? $request->input("parent_id") : null,
            'application_id' => $request->has("application_id") ? $request->input("application_id") : null
        ];
        $data = array_filter($data, function ($item) {
            return !(is_null($item));
        });
        return $data;
    }
}
