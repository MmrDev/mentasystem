<?php

namespace Modules\Account\Entities;

/**
 * Modules\Account\Entities
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @mixin \Eloquent
 */

use Illuminate\Database\Eloquent\Model;
use Modules\Club\Entities\ClubLevel;

class Group extends Model
{
    public $validate = [
        "group_type" => "required",
        "Formula" => "required",
        "Rate_of_receiving_points" => "required",
    ];
    protected $fillable = [
        "group_type",
        "Formula",
        "Rate_of_receiving_points",
    ];

    public function accounts()
    {
        return $this->belongsToMany(Account::class, "account_group", "group_id", "account_id");
    }

    public function clubLevel()
    {
        return $this->hasOne(ClubLevel::class);
    }
}
