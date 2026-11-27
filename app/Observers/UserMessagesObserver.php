<?php

namespace App\Observers;

use App\Model\Attachment;
use App\Model\UserMessage;
use App\Providers\AttachmentServiceProvider;
use Illuminate\Support\Facades\Log;

class UserMessagesObserver
{
    /**
     * Listen to the Attachment deleted event.
     *
     * @param  UserMessage  $userMessage
     * @return void
     */
    public function deleting(UserMessage $userMessage)
    {
        // The parent FK removes placements; shared bytes and the sender's Vault remain intact.
    }
}
