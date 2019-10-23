<?php

namespace Modules\Account\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Account\Entities\Account;

class accountRegisterEvent
{
    use SerializesModels;
    /**
     * @var Account
     */
    public $account;
    public $sms_code;

    /**
     * Create a new event instance.
     * @param int $sms_code
     * @param Account $account
     * @return void
     */
    public function __construct(Account $account, $sms_code)
    {
        //
        $this->account = $account;
        $this->sms_code = $sms_code;
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
