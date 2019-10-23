<?php

namespace Mentasystem\Wallet\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Support\Facades\Queue;
use Mentasystem\Wallet\Events\CreatedOrderEvent;
use Mentasystem\Wallet\Jobs\CreateTransactionJob;
class CreatedOrderListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param CreatedOrderEvent $event
     */
    public function handle(CreatedOrderEvent $event)
    {
        $request = isset($event->request) ? $event->request : null;
        $fromAccountInstance = isset($event->fromAccountInstance) ? $event->fromAccountInstance : null;
        $toAccountInstance = isset($event->toAccountInstance) ? $event->toAccountInstance : null;
        $treasuryInstance = isset($event->treasuryInstance) ? $event->treasuryInstance : null;
        $productOrderInstance = isset($event->productOrderInstance) ? $event->productOrderInstance : null;
        //call job to create transaction
        CreateTransactionJob::dispatchNow(
            $event->order,
            $request,
            $fromAccountInstance,
            $toAccountInstance,
            $treasuryInstance,
            $productOrderInstance
        );
//            ->onQueue("transaction")
//            ->delay(10);
    }
}
