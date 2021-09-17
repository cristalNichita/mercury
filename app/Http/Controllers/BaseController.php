<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $per_page;

    public function __construct(Request $request)
    {
        $this->per_page = (int)$request->input('per_page') ?: 15;
    }

    /**
     * Методы нужны для упрощения а не для повторения функционала
     *
     * Код ошибки обычно на фронту не смотрится как и поле status
     * Использование response - очень плохая идея - на фронте это
     * превратится в response.data.response...
     * @deprecated
     */
    public function sendResponse($response, $status, $code = 200)
    {
        return response()->json(['status' => $status, 'response' => $response], $code);
    }

    public function sendError($error, $code = 500)
    {
        return response()->json(['error' => $error], $code);
    }

    public function sendSuccess($data)
    {
        return response()->json($data);
    }
}
