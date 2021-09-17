<?php

namespace Modules\User\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

/**
 * @deprecated
 */
class ApiUserSettingsController extends BaseController
{
    public function update(Request $request)
    {
        $user = $request->user()->update($request->input());
        return response()->json($user);
    }
}
