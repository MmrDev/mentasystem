<?php

namespace Mentasystem\Wallet;

use Illuminate\Support\ServiceProvider;

class WalletServiceProvider extends ServiceProvider
{
    public function boot()
    {
        include __DIR__ . '/routes/routes.php';
    }

    public function register()
    {
        $this->app->make('Mentasystem\Wallet\WalletController');
    }
}
