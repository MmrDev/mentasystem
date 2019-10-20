<?php

namespace Modules\Account\Entities;

use Illuminate\Database\Eloquent\Model;

class AccountAttribute extends Model
{
    public $validate = [
        "attribute" => "required",
        "status" => "required",
    ];
    protected $table = 'attributes';
    protected $fillable = ['attribute', 'status'];

    public function accounts()
    {
        return $this->belongsToMany(Account::class, 'values', 'attribute_id', 'account_id')->withPivot(["value", "status"]);
    }
}
