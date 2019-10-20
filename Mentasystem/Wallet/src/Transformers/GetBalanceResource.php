<?php

namespace Modules\Wallet\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class getBalanceResource extends Resource
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
            "from_account_id" => $this->from_account_id,
            "to_account_id" => $this->to_account_id,
            "uuid" => $this->uuid,
            "good_id" => $this->good_id,
            "amount" => $this->amount,
            "author" => $this->author,
            "reverse" => $this->reverse,
            "extraValue" => $this->extraValue,
            "goodExtraValue" => $this->goodExtraValue,
            "parent_id" => $this->parent_id,
            "application_id" => $this->application_id,
            "revoked" => $this->revoked,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
