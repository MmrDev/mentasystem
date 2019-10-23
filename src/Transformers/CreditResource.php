<?php

namespace Modules\Account\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class CreditResource extends Resource
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
            "account_id" => $this->account_id,
            "amount" => $this->amount,
            "treasury" => $this->treasury,
        ];
    }
}
