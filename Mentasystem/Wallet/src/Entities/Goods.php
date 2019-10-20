<?php

namespace Modules\Wallet\Entities;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    public $validate = [
        "title" => "required",
        "subtitle" => "required",
        "description" => "required",
        "revoked" => "required",
    ];
    protected $fillable = [
        "title",
        "subtitle",
        "description",
        "revoked",
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, "goods_id");
    }
}
