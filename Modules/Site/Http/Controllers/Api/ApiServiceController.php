<?php

namespace Modules\Site\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Modules\Site\Entities\Page;

class ApiServiceController extends BaseController
{
    public function index(Request $request)
    {
        $services = Page::services()
            ->active()
            ->filter($request->get('filter', []))
            ->sort($request->get('sort', 'id-asc'))
            ->paginate(100);


        return response()->json($services);
    }

    public function show(Page $service)
    {
        abort_if($service->type != Page::SERVICE_TYPE  && !$service->active, 404, 'Сервис не был найден');

        return $service;
    }
}
