<?php


namespace Modules\Settings\Dict;


use Illuminate\Support\Facades\Facade;

class DictFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'dict';
    }
}
