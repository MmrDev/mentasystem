<?php

namespace Modules\Wallet\Events;

use Illuminate\Queue\SerializesModels;

class GetCampaignEvent
{
    use SerializesModels;

    public $eventInstance, $orderInstance, $clubInstance;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($eventInstance, $orderInstance, $clubInstance)
    {
        $this->eventInstance = $eventInstance;
        $this->orderInstance = $orderInstance;
        $this->clubInstance = $clubInstance;
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
