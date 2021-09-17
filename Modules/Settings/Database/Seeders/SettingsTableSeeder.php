<?php

namespace Modules\Settings\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Settings\Entities\Setting;

class SettingsTableSeeder extends Seeder
{
    /**
     * Заполняем первичные настройки
     *
     * @return void
     */
    public function run()
    {
        Setting::create(['name' => 'phone', 'value' => '8 800 250-04-60']);
        Setting::create(['name' => 'email', 'value' => 'mercury.vd@ya.ru']);
        Setting::create(['name' => 'vk', 'value' => 'https://vk.com']);
        Setting::create(['name' => 'facebook', 'value' => 'https://facebook.com']);
        Setting::create(['name' => 'instagram', 'value' => 'https://www.instagram.com/']);
        Setting::create(['name' => 'example_html', 'value' => '<div class="text-h1">Пример <b>html</b> кода</div>']);
        Setting::create(['name' => 'example_doc', 'value' => '<div class="text-h1">Пример <b>визуального редактора</b> кода</div>']);
        Setting::create(['name' => 'tinymce_key', 'value' => 'b3eib43bg414h830wh099so8kkpcp3ev6rpom0nuz9ffi17x']);
        Setting::create(['name' => 'dadata_token', 'value' => 'b0861fad98a8b8c9caf855acb2f52e8eac6f0e8e']);
        Setting::create(['name' => 'dadata_secret', 'value' => 'ee645b47f1204bfb9266d995a61d66e8ea6d0d82']);

        Setting::create(['name' => 'uniteller__login', 'value' => '6649']);
        Setting::create(['name' => 'uniteller__password', 'value' => 'MapcJ937KSzWXzHC8VM2hMTHRsvJqP7wURIduvXpXUjfyfdSvhrosrYMYSCKoTs55usUIVKsO5tF2RcJ']);
        Setting::create(['name' => 'uniteller__point_id', 'value' => '00026512']);

    }
}
