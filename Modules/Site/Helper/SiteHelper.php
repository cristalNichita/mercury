<?php


namespace Modules\Site\Helper;


use Illuminate\Support\Str;
use Modules\Site\Entities\Page;

class SiteHelper
{
    static function transformSlug ($slug, $title, $type)
    {
        if (empty($slug)) {
            $slug = Str::slug($title);
        }

        $slug = Str::limit($slug, 60, '');

        $count_page_slug_type = Page::where('slug', $slug)->where('type', $type)->count();
        $count_page_type = Page::where('type', $type)->count();
        if ($count_page_slug_type) {
            $slug .= '-'. ++$count_page_type;
        }
        return $slug;
    }
}
