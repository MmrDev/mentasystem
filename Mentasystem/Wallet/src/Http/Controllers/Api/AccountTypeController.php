<?php

namespace Modules\Wallet\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Wallet\Entities\AccountType;
use Modules\Wallet\repo\AccountTypeDB;

class AccountTypeController extends Controller
{
    /**
     *
     */
    public function index(Request $r)
    {
        return AccountType::where($r->filters[0][0], $r->filters[0][1], $r->filters[0][2])
            ->paginate($r->input("page_limit"), ["*"], 'page', $r->input("page_number"));
    }

    /**
     * create account type
     * @param Request $request
     * @param AccountTypeDB $accountTypeDB
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, AccountTypeDB $accountTypeDB)
    {
        //create account type for club
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
