<?php

namespace Modules\Site\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Modules\Site\Entities\Page;
use Modules\Site\Transformers\PageResource;

class InfoBlockPageController extends PageController
{
    const PAGE_TYPE = 'info';

    public function index(Request $request)
    {
        $info_blocks = Page::InfoPages()
            ->filter($request->get('filter', []))
            ->sort($request->get('sort', 'id-asc'))
            ->paginate(15);

        return Inertia::render('Site/Page/PageIndex', [
            'pages' => $info_blocks,
            'pageType' => self::PAGE_TYPE,
            'pageTypes' => Page::types
        ]);
    }
}
