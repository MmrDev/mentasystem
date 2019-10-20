<?php

namespace Modules\Account\Http\Controllers\Api;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Account\Entities\Account;
use Modules\Account\Http\Requests\createAccountRequest;
use Modules\Account\Http\Requests\loginRequest;
use Modules\Account\repo\AccountDB;

class ApiController extends Controller
{
    /**
     * Display a listing of the accounts.
     * @param AccountDB $repository
     * @param int $limit
     * @return Response
     */
    public function index(AccountDB $repository, $limit = null)
    {
        $accounts = $repository->getAccounts($limit);
        return response()->json(['userData' => $accounts], 200);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('account::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param createAccountRequest $request
     * @param AccountDB $repository
     * @return Response
     */
    public function doRegister(createAccountRequest $request, AccountDB $repository)
    {
        $accountInstance = $repository->insertAccount($repository->convertRequestToArray($request));
        if ($accountInstance instanceof Account) {
            return response()->json(["response" => $accountInstance], 201);
        }
        return response()->json(["response" => "some things went wrong"], 500);
    }

    /**
     * login user.
     * @param loginRequest $request
     * @param AccountDB $repository
     * @return mixed
     */
    public function doLogin(loginRequest $request, AccountDB $repository)
    {
        if (\Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
            return response()->json(["token" => Auth::user()->generateToken(), 'response' => Auth::check()], 201);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @param AccountDB $repository
     * @return Response
     */
    public function show(AccountDB $repository, $id)
    {
        $account = $repository->getAccount($id);
        return response()->json(['response' => $account], 200);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('account::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param AccountDB $repository
     * @param createAccountRequest $request
     * @param int $id
     * @return Response
     */
    public function update(createAccountRequest $request, AccountDB $repository, $id)
    {
        $repository->updateAccount($repository->convertRequestToArray($request));
        return response()->json(['msg' => 'user update success'], 202);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @param AccountDB $repository
     * @return Response
     */
    public function destroy(AccountDB $repository, $id)
    {
        $repository->deleteAccount($id);
        return response()->json([], 204);
    }
}
