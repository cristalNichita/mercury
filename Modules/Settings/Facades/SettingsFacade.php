<?php


namespace Modules\Settings\Facades;


use Illuminate\Support\Facades\Facade;
use Modules\Settings\Helpers\SettingsHelper;

class SettingsFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'Settings';
    }
}
