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
use Modules\Product\Entities\Cost;

class Wallet extends Model
{
    public $validate = [
        "title" => "required",
        "type" => "required",
    ];
    protected $fillable = [
        "title",
        "type",
        "revoked",
    ];


    public function accountTypes()
    {
        return $this->hasMany(AccountType::class, "wallet_id");
    }

    public function costs()
    {
        return $this->hasMany(Cost::class, "wallet_id");
    }
}
