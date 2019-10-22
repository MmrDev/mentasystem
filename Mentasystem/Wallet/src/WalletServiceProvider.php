<?php

namespace Mentasystem\Wallet;

use Illuminate\Support\ServiceProvider;
use Mentasystem\Wallet\Events\WalletCreatedEvent;
use Mentasystem\Wallet\Listeners\WalletCreatedListener;
use Mentasystem\Wallet\Events\CreatedOrderEvent;
use Mentasystem\Wallet\Listeners\CreatedOrderListener;

class WalletServiceProvider extends ServiceProvider
{
    protected $listen = [
        //        wallet register event
        WalletCreatedEvent::class => [
            WalletCreatedListener::class
        ],

        //        transaction event
        CreatedOrderEvent::class => [
            CreatedOrderListener::class
        ],
    ];

    public function boot()
    {
        include __DIR__ . '/Routes/api.php';
    }

    public function register()
    {
        $this->publishes([__DIR__ . '/Database/', database_path("migrations")], "migration");
    }
}
