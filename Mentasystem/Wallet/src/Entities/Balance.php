<?php

namespace Modules\Wallet\Entities;

use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    public $validate = [
        "from_account_id" => "required",
        "to_account_id" => "required",
        "good_id" => "required",
        "amount" => "required",
        "revoked" => "required",
        "author" => "required",
        "uuid" => "required",
        "reverse" => "required",
        "extraValue" => "required",
        "goodExtraValue" => "required",
        "application_id" => "required",
    ];
    protected $fillable = [
        'from_account_id',
        'to_account_id',
        'good_id',
        'amount',
        'revoked',
        'author',
        'uuid',
        'reverse',
        'extraValue',
        'goodExtraValue',
        'parent_id',
        'application_id',
    ];

}
