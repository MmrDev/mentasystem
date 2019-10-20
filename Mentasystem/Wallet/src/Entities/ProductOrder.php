<?php

namespace Modules\Wallet\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\Cost;
use Modules\Product\Entities\Product;

class ProductOrder extends Model
{
    protected $fillable = [
        "from_user_id",
        "to_user_id",
        "payed_at",
    ];


    public function costs()
    {
        return $this->belongsToMany(Cost::class,
            "order_cost",
            "order_id",
            "cost_id")
            ->withPivot("quantity", "title", "description", "revoked");
    }

    public function orders()
    {
        return $this->hasMany(Order::class, "goods_id");
    }

    public function user()
    {
        return $this->belongsTo(User::class, "to_user_id");
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
