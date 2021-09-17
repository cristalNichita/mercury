<?php

namespace Modules\Site\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Site\Entities\Page;

class NewsController extends PageController
{
    const PAGE_TYPE = 'news';

    public function index(Request $request)
    {
        $news = Page::news()
            ->filter($request->get('filter', []))
            ->sort($request->get('sort', 'id-asc'))
            ->paginate(15);

        return Inertia::render('Site/Page/PageIndex', [
            'pages' => $news,
            'pageType' => self::PAGE_TYPE,
            'pageTypes' => Page::types
        ]);
    }

    public function edit(Page $page)
    {
        return Inertia::render('Site/Page/PageEdit', [
            'page' => $page,
            'pageType' => static::PAGE_TYPE,
            'pageTypes' => Page::types
        ]);
    }
}
