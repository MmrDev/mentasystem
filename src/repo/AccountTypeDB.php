<?php
/**
 * Created by PhpStorm.
 * User: a_nikookherad
 * Date: 9/21/19
 * Time: 2:57 PM
 */

namespace Mentasystem\Wallet\repo;


use Mentasystem\Wallet\Entities\Account;
use Mentasystem\Wallet\Entities\AccountType;

class AccountTypeDB
{

    /**
     * @return mixed
     */
    public function list()
    {
        $instance = AccountType::paginate(10);
        return $instance;
    }

    /**
     * @param $data
     * @return bool
     */
    public function create($data)
    {
        $instance = AccountType::create($data);
        if ($instance instanceof AccountType) {
            return $instance;
        }
        return false;
    }

    /**
     * @param $account_id
     * @return mixed
     */
    public function getAccountTypeWithAccountId($account_id)
    {
        $account = Account::where("id", $account_id)->with("accountType")->first();
        return $account->accountType->type;
    }

    /**
     * get all treasury account type
     */
    public function getTreasury()
    {
        $instances = AccountType::where("type", "treasury")->get();
        return $instances;
    }

    /**
     * @param $accountType
     * @return mixed
     */
    public function getAccountTypeWithAccountType($accountType)
    {
        // get account type instance
        $instances=AccountType::where("type",$accountType)->get();
        return $instances;
    }
}
