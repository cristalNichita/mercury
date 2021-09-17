<?php

namespace Modules\Settings\Http\Controllers\Api;

use App\Http\Controllers\BaseController;


class ApiSettingsController extends BaseController
{

    protected $private_settings = [
        'tinymce_key',
        'uniteller__point_id',
        'uniteller__password',
        'uniteller__login'
    ];

    public function __invoke()
    {
        return \Arr::except(\Settings::get(), $this->private_settings);
    }
}
