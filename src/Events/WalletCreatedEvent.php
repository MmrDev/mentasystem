<?php

namespace Mentasystem\Wallet\Events;

use Illuminate\Queue\SerializesModels;

class WalletCreatedEvent
{
    use SerializesModels;
    public $wallet;
    public $data;

    /**
     * WalletCreatedEvent constructor.
     * @param $wallet
     * @param $data
     */
    public function __construct($wallet, $data)
    {
        $this->wallet = $wallet;
        $this->data = $data;
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
