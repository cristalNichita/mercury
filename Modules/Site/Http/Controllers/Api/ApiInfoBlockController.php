<?php

namespace Modules\Site\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use Modules\Site\Entities\InfoBlock;

class ApiInfoBlockController extends BaseController
{
    public function index()
    {
        $infoBlocks = InfoBlock::with('page')->InMain()->get();

        return $infoBlocks->toArray();
    }
}
