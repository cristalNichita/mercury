<?php

namespace Modules\User\Events;

use Illuminate\Queue\SerializesModels;

class CompanyCreated
{
    use SerializesModels;

    public $company;

    public function __construct($company)
    {
        $this->company = $company;
    }

}
