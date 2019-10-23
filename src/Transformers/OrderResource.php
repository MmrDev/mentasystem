<?php

namespace Mentasystem\Wallet\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Mentasystem\Wallet\Entities\Account;
use Mentasystem\Wallet\Entities\AccountType;

class   OrderResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
//        return parent::toArray($request);

        //       wallet type
        $walletType = AccountType::find
        (Account::find($this->treasury_account_id)
            ->toArray()["account_type_id"])
        ["title"];

        $fromUserInstance = $this->getUser($this->from_account_id);
        $toUserInstance = $this->getUser($this->to_account_id);

        return [
            "id" => $this->id,
            "goods_id" => $this->goods_id,
            "customer_mobile" => isset($fromUserInstance) ? $fromUserInstance->mobile : null,
            "customer_name" => isset($fromUserInstance) ? $fromUserInstance->name : null,
            "merchant_mobile" => isset($toUserInstance) ? $toUserInstance->mobile : null,
            "merchant_name" => isset($toUserInstance) ? $toUserInstance->name : null,
            "amount" => $this->amount,
            "paid_at" => $this->paid_at,
            "treasury_account_id" => $this->treasury_account_id,
            "walletType" => isset($walletType) ? $walletType : null,
            "created_at" => date("Y-m-d H:i:s", strtotime($this->created_at)),
            "updated_at" => date("Y-m-d H:i:s", strtotime($this->updated_at)),
            "transaction" => TransactionResource::collection($this->transactions)
//            "transaction" => TransactionResource::collection($this->whenLoaded("transactions"))
        ];
    }

    /**
     * @param $accId
     * @return mixed
     */
    private function getUser($accId)
    {
        return Account::where("id", $accId)->with("user")->first()->user;
    }
}
