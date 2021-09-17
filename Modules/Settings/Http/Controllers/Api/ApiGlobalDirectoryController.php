<?php


namespace Modules\Settings\Http\Controllers\Api;


use App\Http\Controllers\BaseController;
use Modules\Settings\Dict\DictFacade;

class ApiGlobalDirectoryController extends BaseController
{
    public function __invoke($dict)
    {
        $result = DictFacade::get($dict);
        return $this->sendSuccess($result);
    }
}
