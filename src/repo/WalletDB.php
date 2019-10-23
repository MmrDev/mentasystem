<?php
/**
 * Created by PhpStorm.
 * User: a_nikookherad
 * Date: 9/15/19
 * Time: 6:21 PM
 */

namespace Mentasystem\Wallet\repo;


use Mentasystem\Wallet\Entities\Wallet;

/**
 * Class WalletDB
 * @package Modules\Wallet\repo
 */
class WalletDB
{

    /**
     * get all wallets
     * @return \Illuminate\Database\Eloquent\Collection|Wallet[]
     */
    public function list()
    {
        return Wallet::paginate(10);
    }

    /**
     * @param $data
     * @return bool
     */
    public function create($data)
    {
        $wallet = Wallet::create($data);
        if ($wallet instanceof Wallet) {
            return $wallet;
        }
        return false;
    }

    /**
     * @param $type
     * @return bool
     */
    public function get($type)
    {
        $instance = Wallet::where("title", $type)->first();
        if ($instance instanceof Wallet) {
            return $instance;
        }
        return false;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return $instance = Wallet::find($id)->delete();
    }

    /**
     * @param $id
     * @param $data
     * @return bool
     */
    public function update($id, $data): bool
    {
        return $instance = Wallet::where("id", $id)->update($data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $instance = Wallet::find($id);
    }
}
