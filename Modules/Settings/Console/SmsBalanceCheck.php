<?php

namespace Modules\Settings\Console;

use App\Helpers\Helper;
use Settings;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Modules\Settings\Emails\SmsBalanceReport;

class SmsBalanceCheck extends Command
{
    protected $signature = 'sms:balance-check';
    protected $description = 'Проверка баланса для SMS';

    public function handle()
    {
        $this->info($this->description);
        $this->newLine();

        $balance = Helper::getSmsBalance();

        $this->line('Код: ' . $balance['code']);
        $this->line('Баланс: ' . $balance['balance']);

        if (($balance['code'] != 100) || ($balance['balance'] < 50)) {
            $emails = ['dev@echo-company.ru', Settings::get('email')];
            $this->line('Отправляем письмо уведомление: ' . implode(', ', $emails));
            Mail::to($emails)->send(new SmsBalanceReport($balance));
        }

    }
}
