<?php

namespace Modules\Settings\Http\Controllers;

use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Modules\Settings\Entities\GlobalDirectory;
use Modules\Settings\Events\SettingsLinksEvent;
use Modules\Settings\Http\Requests\GlobalDirectoryRequest;

class GlobalDirectoryController extends Controller
{
    public function __construct()
    {
        Inertia::share('sidebarLinks', event(new SettingsLinksEvent()));
    }

    public function index()
    {
        $directories = GlobalDirectory::paginate(999);
        return Inertia::render('Settings/GlobalDirectory/GlobalDirectoryIndex', [
            'directories' => $directories
        ]);
    }

    public function create()
    {
        return Inertia::render('Settings/GlobalDirectory/GlobalDirectoryCreate', [
            'directory' => new GlobalDirectory()
        ]);
    }

    public function store(GlobalDirectoryRequest $request)
    {
        $validated = $request->validated();
        GlobalDirectory::create($validated);

        return redirect(route('settings.directory'));
    }

    public function edit(GlobalDirectory $directory)
    {
        $directory->load('items');
        return Inertia::render('Settings/GlobalDirectory/GlobalDirectoryEdit', [
            'directory' => $directory
        ]);
    }

    public function update(GlobalDirectoryRequest $request, GlobalDirectory $directory)
    {
        $validated = $request->validated();
        $directory->update($validated);

        return redirect(route('settings.directory'));
    }

    public function destroy(GlobalDirectory $directory)
    {
        $directory->delete();

        return redirect(route('settings.directory'));
    }
}
