<?php


namespace Modules\Integration\Helpers;


use Carbon\Carbon;
use Illuminate\Support\Collection;

class ArrHelper
{


    static function recursivelyCollect($item) {
        if (is_array($item) || is_object($item)) {

            $item = collect($item);

            foreach($item as $key => $val) {

                if($key === '@attributes') {
                    $item = $val;
                } else {
                    $item[$key] = self::recursivelyCollect($val);
                }
            }
        }

        return $item;
    }

    static function clearData(array $attributes, string $key, string $type = null, $default = null) {

        $value = trim(data_get($attributes, $key));

        if($value && $type) {

            if($type === 'date') {
                $value = Carbon::parse($value);
            }
            elseif($type === 'float') {

                str_replace(',', '.', $value);
                $value = (float)$value;
            }
        }

        return $value ?: $default;
    }

    static function getFIO(string $field = '', $deter = ' ') {

        $fio = explode($deter, $field);
        $name = '';
        $last_name = '';
        $middle_name = '';

        if(count($fio) === 1) {
            $name = trim($fio[0]);
        }

        if(count($fio) > 1) {

            $last_name = isset($fio[0]) ? trim($fio[0]) : '';
            $name = isset($fio[1]) ? trim($fio[1]) : '';
            $middle_name = isset($fio[2]) ? trim($fio[2]) : '';

        }

        return [
            'lastname' => $last_name,
            'name' => $name,
            'middlename' => $middle_name,
        ];


    }
}
