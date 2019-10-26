<?php

namespace Mentasystem\Wallet\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Mentasystem\Wallet\Entities\AccountType;
use Mentasystem\Wallet\repo\AccountTypeDB;

class AccountTypeController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $accountTypeDB = new AccountTypeDB();
        $response = $accountTypeDB->list();
        return response()
            ->json([
                "message" => "account type list",
                "data" => $response
            ], 200);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $accountTypeDB = new AccountTypeDB;

        //create account type
        $accountTypeData = $request->all();

        $accountTypeInstance = $accountTypeDB->create($accountTypeData);

        if ($accountTypeInstance) {
            return \response()
                ->json([
                    "message" => "account type successfully created"
                ], 200);
        }
        return \response()
            ->json([
                "message" => "some things went wrong"
            ], 400);
    }

    /**
     * @param $id
     */
    public function show($id)
    {
        //
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
     */
    public function destroy($id)
    {
        //
    }
}
