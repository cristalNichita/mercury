<?php

namespace Modules\Catalog\Http\Controllers;

use App\Http\Controllers\BaseController;

class CatalogController extends BaseController
{
    public function index()
    {
        return redirect()->route('catalog.products');
    }
}
