<?php

namespace Modules\Site\Http\Controllers;

use Illuminate\Routing\Controller;

class SiteController extends Controller
{
    public function index()
    {
        return redirect()->route('site.main-sliders');
    }
}
