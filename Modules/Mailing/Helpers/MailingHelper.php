<?php


namespace Modules\Mailing\Helpers;


use Illuminate\Support\Facades\Config;

class MailingHelper
{
    static function getConfigEvent () {
        return collect(Config::get('mailing.subscribe'))->toArray();
    }
}
