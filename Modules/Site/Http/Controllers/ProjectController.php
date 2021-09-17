<?php

namespace Modules\Site\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Modules\Settings\Dict\DictFacade;
use Modules\Site\Entities\Page;
use Modules\Site\Http\Requests\PageRequest;
use Modules\Site\Transformers\PageResource;

class ProjectController extends PageController
{
    const PAGE_TYPE = 'projects';
    const DICT_NAME = 'project-categories';

    public function index(Request $request)
    {
        $projects = Page::projects()
            ->filter($request->get('filter', []))
            ->sort($request->get('sort', 'id-asc'))
            ->paginate(15);

        return Inertia::render('Site/Page/PageIndex', [
            'pages' => $projects,
            'pageType' => self::PAGE_TYPE,
            'pageTypes' => Page::types
        ]);
    }

    public function create() {
        $page = new Page();
        $page->type = Page::types[static::PAGE_TYPE];

        $categories = DictFacade::get(self::DICT_NAME);

        return Inertia::render('Site/Page/PageCreate', [
            'page' => $page,
            'pageType' => static::PAGE_TYPE,
            'pageTypes' => Page::types,
            'categories' => $categories,
        ]);
    }

    public function edit(Page $page)
    {
        $categories = DictFacade::get(self::DICT_NAME);

        return Inertia::render('Site/Page/PageEdit', [
            'page' => $page,
            'pageType' => static::PAGE_TYPE,
            'pageTypes' => Page::types,
            'categories' => $categories,
        ]);
    }
}
