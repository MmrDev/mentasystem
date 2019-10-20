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
use Modules\Account\Entities\Credit;
use Modules\Product\Entities\Cost;

class Order extends Model
{
    public $validate = [
        "user_id" => "required",
        "goods_id" => "required",
        "from_account_id" => "required",
        "to_account_id" => "required",
        "refund" => "required",
        "cashout" => "required",
        "amount" => "required",
        "treasury_account_id" => "required",
    ];
    protected $fillable = [
        "user_id",
        "goods_id",
        "type",
        "from_account_id",
        "to_account_id",
        "refund",
        "cashout",
        "paid_at",
        "amount",
        "treasury_account_id",
    ];


    public function goods()
    {
        return $this->belongsTo(Goods::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, "order_id");
    }

    public function credit()
    {
        return $this->belongsTo(Credit::class);
    }

    public function productOrder()
    {
        return $this->belongsTo(ProductOrder::class, "goods_id");
    }
}
