<?php

namespace Modules\Wallet\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class GoodsResource extends Resource
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
            "title" => $this->title,
            "subtitle" => $this->subtitle,
            "description" => $this->description,
            "revoked" => $this->revoked,
        ];
    }
}
