<?php

namespace Modules\Site\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Modules\Site\Entities\InfoBlock;
use Modules\Site\Entities\Page;
use Modules\Site\Http\Requests\InfoBlockRequest;
use Modules\Site\Transformers\InfoBlockResource;

class InfoBlockController extends Controller
{

    public function index()
    {
        $infoBlocks = InfoBlock::with('page')->paginate(15);
        return Inertia::render('Site/InfoBlock/InfoBlockIndex', [
            'infoBlocks' => $infoBlocks
        ]);
    }

    public function create()
    {
        return Inertia::render('Site/InfoBlock/InfoBlockCreate', [
            'infoBlock' => new InfoBlock()
        ]);
    }

    public function store(InfoBlockRequest $request)
    {
        $validated = $request->validated();
        $infoBlock = InfoBlock::create($validated);

        if ($infoBlock->slug) {
            $page_data['title'] = $validated['title'];
            $page_data['slug'] = $validated['slug'];
            $page_data['type'] = Page::INFO_BLOCKS;
            $page_data['content'] = $validated['description'];
            $infoBlock->page()->create($page_data);
        }

        if ($request->has('newImage'))
        {
            $infoBlock->setImage($validated['newImage']);
        }

        return redirect(route('site.info-blocks'));
    }

    public function edit(InfoBlock $infoBlock)
    {
        return Inertia::render('Site/InfoBlock/InfoBlockEdit', [
            'infoBlock' => $infoBlock->load('page')
        ]);
    }

    public function update(InfoBlockRequest $request, InfoBlock $infoBlock)
    {
        $old_slug = $infoBlock->slug;

        $validated = $request->validated();
        $infoBlock->update($validated);

        $page = Page::where('slug', $old_slug)->InfoPages()->first();
        if ($infoBlock->slug !== $old_slug || is_null($page)) {
            $page_data['title'] = $validated['title'];
            $page_data['slug'] = $validated['slug'];
            $page_data['content'] = $validated['description'];
            $page_data['type'] = Page::INFO_BLOCKS;

            if ($page) {
                $page->update(['slug' => $page_data['slug']]);
            } else {
                Page::create($page_data);
            }
        }

        if ($request->has('newImage'))
        {
            $infoBlock->setImage($validated['newImage']);
        }

        return redirect(route('site.info-blocks'));
    }

    public function destroy(InfoBlock $infoBlock)
    {
        $infoBlock->page()->delete();
        $infoBlock->delete();

        return redirect(route('site.info-blocks'));
    }

    public function removeImage(InfoBlock $infoBlock)
    {
        $infoBlock->deleteImage();

        return redirect()->back();
    }

    public function toggleInMain(InfoBlock $infoBlock)
    {
        $infoBlock->update(['in_main' => !$infoBlock->in_main]);

        return redirect()->back();
    }
}
