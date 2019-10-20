<?php

namespace Modules\Wallet\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Modules\Club\Entities\Club;

/**
 * Modules\Account\Entities
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @mixin \Eloquent
 */
class Account extends Model
{

    public $validate = [
        "account_type_id" => "required",
        "treasury_id" => "required",
        "user_id" => "required",
        "revoked" => "required",
    ];
    use Notifiable, HasApiTokens;
    protected $table = 'accounts';
    protected $fillable = [
        "account_type_id",
        "treasury_id",
        "user_id",
        "revoked",
    ];

    //delete credit record when we delete account
    /*    public static function boot()
        {
            parent::boot();

            static::deleting(function ($account) { // before delete() method call this
                $account->credit()->delete();
                // do the rest of the cleanup...
            });
        }*/

    //authentication with custom username
    /*    public function findForPassport($username)
        {
            return $this->where('username', $username)->first();
        }*/

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function credits()
    {
        return $this->hasMany(Credit::class, "account_id");
    }

    public function accountType()
    {
        return $this->belongsTo(AccountType::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, "account_group", "account_id", "group_id");
    }

    public function account()
    {
        return $this->hasOne(Account::class, "treasury_id", "id");
    }


    public function clubs()
    {
        return $this->belongsToMany(Club::class, "club_accounts", "account_id", "club_id")->withPivot("type", "expired_at", "revoked");
    }

}
