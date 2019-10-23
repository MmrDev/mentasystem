<?php

namespace Mentasystem\Wallet\Events;

use Illuminate\Queue\SerializesModels;
use Mentasystem\Wallet\Entities\Order;

class CreatedOrderEvent
{
    use SerializesModels;
    public $order;
    public $request;
    public $fromAccountInstance;
    public $toAccountInstance;
    public $treasuryInstance;

    /**
     * CreatedOrderEvent constructor.
     * @param Order $order
     * @param $request
     * @param $fromAccountInstance
     * @param $toAccountInstance
     * @param $treasuryInstance
     * @param $productOrderInstance
     */
    public function __construct(Order $order, $request = null, $fromAccountInstance = null, $toAccountInstance = null, $treasuryInstance = null)
    {
        $this->order = $order;
        $this->request = $request;
        $this->fromAccountInstance = $fromAccountInstance;
        $this->toAccountInstance = $toAccountInstance;
        $this->treasuryInstance = $treasuryInstance;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
