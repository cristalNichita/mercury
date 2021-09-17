<?php

namespace Modules\Settings\Emails;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SmsBalanceReport extends Mailable
{
    use SerializesModels;

    public $balance;

    public function __construct($balance)
    {
        $this->balance = $balance;
        $this->subject('Проверка баланса СМС на проекте ' . env('APP_URL'));
    }

    public function build()
    {
        return $this->view('settings::sms_balance_report');
    }
}
