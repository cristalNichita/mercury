<?php

namespace Modules\Site\Http\Controllers\Api;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Settings\Dict\DictFacade;

class ApiContactController extends Controller
{
    const DICT_NAME = 'contacts-page';

    public function index()
    {
        $contacts = DictFacade::get(self::DICT_NAME);

        $result = $contacts->items->map(function ($item) {
            return [
                'title' => $item->name,
                'content' => $item->additions
            ];
        });

        return response()->json($result);
    }
}
