<?php

namespace Modules\Wallet\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Wallet\Entities\AccountType;

class AccountTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        AccountType::truncate();
        factory(AccountType::class, 5)->create();
        // $this->call("OthersTableSeeder");
    }
}
