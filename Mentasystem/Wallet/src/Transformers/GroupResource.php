<?php

namespace Modules\Account\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class GroupResource extends Resource
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
            "Formula" => $this->Formula,
            "Rateـofـreceivingـpoints" => $this->Rateـofـreceivingـpoints,
        ];
    }
}
