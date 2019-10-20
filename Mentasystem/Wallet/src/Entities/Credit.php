<?php

namespace Modules\Wallet\Entities;

/**
 * Modules\Account\Entities
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @mixin \Eloquent
 */

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    public $validate = [
        "account_id" => "required",
        "treasury_id" => "required",
        "title" => "required",
        "currency" => "required",
        "revoked" => "required",
    ];

    protected $fillable = [
        "account_id",
        "club_id",
        "treasury_id",
        "amount",
        "usable_at",
        "expired_at",
        "revoked",
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
