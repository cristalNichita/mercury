<?php

namespace Modules\Site\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Modules\Site\Entities\InfoBlock;
use Modules\Site\Entities\Page;
use Modules\Site\Helper\SiteHelper;
use Modules\Site\Http\Requests\PageRequest;

class PageController extends Controller
{
    const PAGE_TYPE = '';

    public function index(Request $request)
    {
        $pages = Page::filter($request->get('filter', []))
            ->sort($request->get('sort', 'id-asc'))
            ->paginate(15);

        return Inertia::render('Site/Page/PageIndex', [
            'pages' => $pages,
            'pageType' => static::PAGE_TYPE,
            'pageTypes' => Page::types,
        ]);
    }

    public function create()
    {
        $page = new Page();
        $page->type = Page::types[static::PAGE_TYPE];

        return Inertia::render('Site/Page/PageCreate', [
            'page' => $page,
            'pageType' => static::PAGE_TYPE,
            'pageTypes' => Page::types
        ]);
    }

    public function store(PageRequest $request)
    {
        $validated = $request->validated();

        $page = Page::create($validated);

        if ($request->has('newMainImage')) {
            $page->setImage($validated['newMainImage']);
        }

        if ($request->has('newGalleryImages')) {
            $page->addImagesToGallery($validated['newGalleryImages']);
        }

        $page->save();

        return redirect(route('site.' . static::PAGE_TYPE));
    }

    public function edit(Page $page)
    {
        return Inertia::render('Site/Page/PageEdit', [
            'page' => $page,
            'pageType' => static::PAGE_TYPE,
            'pageTypes' => Page::types
        ]);
    }

    public function update(PageRequest $request, Page $page)
    {
        $validated = $request->validated();

        $page->update($validated);

        if ($request->has('newMainImage')) {
            $page->setImage($validated['newMainImage']);
        }

        if ($request->has('newGalleryImages')) {
            $page->addImagesToGallery($validated['newGalleryImages']);
        }

        $page->save();

        return redirect()->back();
    }

    public function removeGalleryImage(Page $page, $id)
    {
        $page->deleteMedia($id);

        return redirect()->back();
    }

    public function toggleActive(Page $page)
    {
        $page->update(['active' => !$page->active]);
        if ($page->active && !$page->published_at && $page->type == Page::NEWS_TYPE) {
            $page->published_at = Carbon::now();
            $page->save();
        }

        return redirect()->back();
    }

    public function destroy(Page $page)
    {
        $info_block = null;
        if (static::PAGE_TYPE === array_search(Page::INFO_BLOCKS, Page::types)) {
            $info_block = $page->infoBlock()->first();
        }

        $page->delete();

        return $info_block
            ? redirect(route('site.info-blocks.edit', ['info_block' => $info_block->id]))
            : redirect(route('site.' . static::PAGE_TYPE));
    }
}
