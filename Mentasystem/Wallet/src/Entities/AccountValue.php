<?php

namespace Modules\Account\Entities;

use Illuminate\Database\Eloquent\Model;

class AccountValue extends Model
{
    public $validate = [
        "value" => "required",
        "status" => "required",
    ];
    protected $fillable = ['value', 'status'];
}
