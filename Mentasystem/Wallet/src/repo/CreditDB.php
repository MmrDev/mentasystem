<?php
/**
 * Created by PhpStorm.
 * User: a_nikookherad
 * Date: 9/17/19
 * Time: 10:17 AM
 */

namespace Mentasystem\Wallet\repo;


use Mentasystem\Wallet\Entities\Credit;

class CreditDB
{

    public function create($date)
    {
        $credit = Credit::create($date);
        if ($credit instanceof Credit) {
            return $credit;
        }
        return false;
    }
}
