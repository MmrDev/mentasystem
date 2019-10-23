<?php

namespace Modules\Wallet\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class TransactionResource extends Resource
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
        return [
            "order_id" => $this->order_id,
            "account_id" => $this->account_id,
            "amount" => $this->amount,
            "reverse" => $this->reverse,
        ];
    }
}
