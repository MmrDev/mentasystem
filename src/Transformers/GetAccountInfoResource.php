<?php

namespace Modules\Account\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class getAccountInfoResource extends Resource
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
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
//            "password" => 123,
            "mobile" => $this->mobile,
            "relation" => $this->when(isset($this->credit), function () {
                return $this->credit;
            })
//            "app_version" => "1.1",
//            "os_version" => "4.4.4",
//            "imei" => "123456789",
//            "db_version" => "5.8",
//            "app_id" => "1234"
        ];
    }
}
