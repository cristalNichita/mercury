<?php

namespace Modules\Site\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Modules\Site\Entities\Page;

class ApiProjectController extends BaseController
{
    public function index(Request $request)
    {
        $projects = Page::projects()
            ->active()
            ->filter($request->get('filter', []))
            ->sort($request->get('sort', 'id-asc'))
            ->paginate(100);


        return response()->json($projects);
    }

    public function show(Page $project)
    {
        abort_if($project->type != Page::PROJECT_TYPE && !$project->active, 404, 'Проект не был найден');

        return $project;
    }
}
