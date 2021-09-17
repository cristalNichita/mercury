<?php

namespace Modules\Site\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Modules\Site\Entities\Page;

class ApiInfoPageController extends BaseController
{
    public function index(Request $request)
    {
        $info_page = Page::InfoPages()
            ->active()
            ->filter($request->get('filter', []))
            ->sort($request->get('sort', 'id-asc'))
            ->paginate($this->per_page);


        return response()->json($info_page);
    }

    public function show(Page $info_page)
    {
        abort_if($info_page->type != Page::INFO_BLOCKS  && !$info_page->active, 404, 'Страница не найдена');

        return $info_page;
    }
}
