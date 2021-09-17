<?php

namespace Modules\User\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Modules\User\Entities\Holding;

class ApiHoldingController extends BaseController
{
    public function __invoke(Request $request)
    {
        $holdins = Holding::filter($request->get('filter', []))->paginate($this->per_page);
        return $holdins;
    }
}
