<?php

namespace Modules\Account\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class accountResourse extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $account = (array)$this->resource;
//                dd($account);
        $attrs = [];
        foreach ($account['account_attributes'] as $attr) {
            $attrs[$attr['id']] = $attr['attribute'];
        }
//        dd($attrs);
        $vals = [];
        foreach ($account['account_attributes'] as $attr) {
            $pivot = $attr['pivot'];
            $vals[$attrs[$pivot['attribute_id']]] = $pivot['value'];
        }
        foreach ($vals as $key => $val) {
            $account[$key] = $val;
        }
//        dd($vals);
        unset($account['account_attributes']);
        unset($account['password']);
        return $account;
    }
}
