<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class Helper
{
    public static function clearPhone(string $phone)
    {
        // Удаляем добавочный код
        $phone = preg_replace('/доб.*/ui', '', $phone);

        $phone = preg_replace('/\D/', '', $phone);

        if ((Str::length($phone) == 10) && ($phone[0] !== '8') && ($phone[0] !== '7')) {
            $phone = '7' . $phone;
        }

        // Заменяем 8 на международный код страны 7
        if (($phone[0] === '8') && (Str::length($phone) === 11)) {
            $phone[0] = 7;
        }

        return $phone;
    }

    public static function getSmsBalance()
    {
        $api_id = env('SMSRU_API_ID');
        $client = new \Zelenin\SmsRu\Api(new \Zelenin\SmsRu\Auth\ApiIdAuth($api_id));
        $balance = $client->myBalance();
        return [
            'code' => $balance->code,
            'balance' => $balance->balance,
            'description' => $balance->getDescription()
        ];
    }
}
