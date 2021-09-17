<?php


namespace Modules\Integration\Helpers;


class OdinHelper
{
    public static function convertParamType($type)
    {
        switch (mb_strtolower($type)) {
            case 'адрес электронной почты':
                return 'email';
            case 'телефон':
                return 'phone';
            case 'адрес':
                return 'address';
            default:
                return $type;
        }
    }

    public static function reverseParamType($type)
    {
        switch (mb_strtolower($type)) {
            case 'email':
                return 'Адрес электронной почты';
            case 'phone':
                return 'Телефон';
            case 'address':
                return 'Адрес';
            default:
                return $type;
        }
    }

}
