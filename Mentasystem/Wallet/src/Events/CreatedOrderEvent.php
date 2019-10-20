<?php

namespace Modules\Wallet\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Wallet\Entities\Order;

class CreatedOrderEvent
{
    use SerializesModels;
    public $order;
    public $request;
    public $fromAccountInstance;
    public $toAccountInstance;
    public $treasuryInstance;
    public $productOrderInstance;

    /**
     * CreatedOrderEvent constructor.
     * @param Order $order
     * @param $request
     * @param $fromAccountInstance
     * @param $toAccountInstance
     * @param $treasuryInstance
     * @param $productOrderInstance
     */
    public function __construct(Order $order, $request = null, $fromAccountInstance = null, $toAccountInstance = null, $treasuryInstance = null, $productOrderInstance = null)
    {
        $this->order = $order;
        $this->request = $request;
        $this->fromAccountInstance = $fromAccountInstance;
        $this->toAccountInstance = $toAccountInstance;
        $this->treasuryInstance = $treasuryInstance;
        $this->productOrderInstance = $productOrderInstance;
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
