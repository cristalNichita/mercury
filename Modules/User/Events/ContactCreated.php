<?php

namespace Modules\User\Events;

use Illuminate\Queue\SerializesModels;

class ContactCreated
{
    use SerializesModels;

    public $contact;

    public function __construct($contact)
    {
        $this->contact = $contact;
    }

}
