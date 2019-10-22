<?php
/**
 * Created by PhpStorm.
 * User: a_nikookherad
 * Date: 8/26/19
 * Time: 3:56 PM
 */

namespace Mentasystem\Wallet\repo;


use Mentasystem\Wallet\Entities\Transaction;

class TransactionDB
{

    public function create($data)
    {
        $trans = Transaction::create($data);
        if ($trans instanceof Transaction) {
            return $trans;
        }
        return false;
    }
}
