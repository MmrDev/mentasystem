<?php

namespace Modules\Account\Entities;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $validate = [
        "name" => "required",
        "status" => "required",
    ];
    protected $fillable = ['name', 'status'];

    public function accounts()
    {
        return $this->belongsToMany(Account::class, 'roles_accounts');
    }
}
