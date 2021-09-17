<?php

namespace Modules\Settings\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Modules\Settings\Entities\GlobalDirectory;
use Modules\Settings\Entities\GlobalDirectoryItem;
use Modules\Settings\Http\Requests\GlobalDirectoryItemRequest;

class GlobalDirectoryItemController extends Controller
{
    public function create(GlobalDirectory $directory)
    {
        return Inertia::render('Settings/GlobalDirectoryItem/GlobalDirectoryItemCreate',[
            'directory' => $directory,
            'item' => new GlobalDirectoryItem(['directory_id' => $directory->id])
        ]);
    }

    public function store(GlobalDirectoryItemRequest $request, GlobalDirectory $directory)
    {
        $validated = $request->validated();
        $directory->items()->save(new GlobalDirectoryItem($validated));

        return redirect(route('settings.directory.edit', $directory->id));
    }

    public function edit(GlobalDirectory $directory, GlobalDirectoryItem $item)
    {
        abort_if($directory->id != $item->directory_id, 403);

        return Inertia::render('Settings/GlobalDirectoryItem/GlobalDirectoryItemEdit',[
            'directory' => $directory,
            'item' => $item
        ]);
    }

    public function update(GlobalDirectoryItemRequest $request, GlobalDirectory $directory, GlobalDirectoryItem $item)
    {
        $validated = $request->validated();
        $item->update($validated);

        return redirect(route('settings.directory.edit', $directory->id));
    }

    public function destroy(GlobalDirectory $directory, GlobalDirectoryItem $item)
    {
        $item->delete();

        return redirect(route('settings.directory.edit', $directory->id));
    }
}
