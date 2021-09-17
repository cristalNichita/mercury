<?php

namespace Modules\Site\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Modules\Site\Entities\Page;

class ApiNewsController extends BaseController
{
    public function index(Request $request)
    {
        $news = Page::news()
            ->active()
            ->filter($request->get('filter', []))
            ->sort($request->get('sort', 'id-asc'))
            ->paginate($this->per_page);


        return response()->json($news);
    }

    public function show(Page $news)
    {
        abort_if($news->type != Page::NEWS_TYPE && !$news->active, 404, 'Новость не была найдена');

        $all_news = Page::news()->orderBy('id', 'desc')->get();
        $news_idx = $all_news->search(function($item) use ($news) {
            return $item->id == $news->id;
        });
        $next_news = isset($all_news[$news_idx + 1])
                        ? $all_news[$news_idx + 1]
                        : null;

        $recommendation = Page::news()
            ->where('id', '!=', $news->id)
            ->orderBy('id', 'desc')
            ->take(3)
            ->get();

        return [
            'news' => $news,
            'recommendation' => $recommendation,
            'next_news' => $next_news,
        ];
    }
}
