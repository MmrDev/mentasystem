<?php

namespace Modules\Wallet\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Wallet\Entities\Account;
use Modules\Wallet\Entities\AccountType;

class AccountCreatedEvent
{
    use SerializesModels;
    public $account;
    public $data;

    /**
     * AccountCreatedEvent constructor.
     * @param Account $account
     * @param $data
     */
    public function __construct(Account $account, $data)
    {
        $this->account = $account;
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
