<?php


namespace Modules\User\Helpers;


use Illuminate\Support\Str;

class PhoneHelper
{

    private const MIN_PHONE_LENGTH = 10;

    /**
     * Очищает телефон от лишних символов и цифр, если его длинна
     * @param string $phone_raw сырой не обработанный номер который может содержать любые символы
     * @return mixed
     * @deprecated used Helper::clearPhone
     */
    public static function clear(string $phone_raw)
    {
        $phone = preg_replace('/\D/', '', $phone_raw);
        if (Str::length($phone) < self::MIN_PHONE_LENGTH) {
            return $phone;
        }
        $phone = '7'.Str::substr($phone, -10);
        return $phone;
    }
}
