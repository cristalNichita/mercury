<?php

namespace Modules\Settings\Helpers;

use Modules\Settings\Entities\GlobalDirectoryItem;
use Modules\Settings\Entities\Setting;

/**
 * Получение настроек системы
 *
 * @example Settings::get(); Settings::get('phone'); Settings::get('not-found');
 * @example Settings::dict('contacts-page');
 *
 * @package Modules\Settings\Helpers
 */
class SettingsHelper
{

    protected $settings = null;

    public function __construct()
    {
        $this->settings = Setting::all()->pluck('value', 'name')->toArray();
    }

    /**
     * Получить значение по имени или весь массив настроек
     *
     * @param null $name - ключ настройки - если null вернет весь массив
     * @return array|mixed|null
     */
    public function get($name = null)
    {
        if (empty($name)) {
            return $this->settings;
        }
        return $this->settings[$name] ?? null;
    }

    public function set($name, $value)
    {
        if (empty($name)) {
            return;
        }

        $this->settings[$name] = $value;

        Setting::updateOrCreate(['name' => $name], ['value' => $value]);
    }

    public function dict($name)
    {
        //TODO: Оптимизировать/Закешировать и т.д.
        return GlobalDirectoryItem::whereHas('directory', function ($query) use ($name) {
            return $query->where('code', $name);
        })->get()->toArray();
    }

}
