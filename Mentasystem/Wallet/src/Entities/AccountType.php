<?php

namespace Modules\Wallet\Entities;

/**
 * Modules\Wallet\Entities
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @mixin \Eloquent
 */

use Illuminate\Database\Eloquent\Model;

class AccountType extends Model
{
    public $validate = [
        "type" => "required",
        "wallet_id" => "required",
        "subtitle" => "required",
        "description" => "required",
        "balance_type" => "required",
        "min_account_amount" => "required",
        "max_account_amount" => "required",
        "min_transaction_amount" => "required",
        "max_transaction_amount" => "required",
        "legal" => "required",
        "interest_rate" => "required",
        "interest_period" => "required",
        "revoked" => "required",
    ];
    protected $fillable = [
        "type",
        "wallet_id",
        "title",
        "subtitle",
        "description",
        "balance_type",
        "min_account_amount",
        "max_account_amount",
        "min_transaction_amount",
        "max_transaction_amount",
        "legal",
        "interest_rate",
        "interest_period",
        "revoked",
    ];

    public function accounts()
    {
        return $this->hasMany(Account::class, "account_type_id");
    }

    public function wallet()
    {
        return $this->belongsto(Wallet::class);
    }
}
