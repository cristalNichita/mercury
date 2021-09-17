<?php

namespace Modules\Mailing\Http\Controllers;

use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Modules\Mailing\Entities\Mailing;
use Modules\Mailing\Entities\MailingEvents;
use Modules\Mailing\Http\Requests\MailingRequest;
use Modules\Settings\Events\SettingsLinksEvent;

class MailingController extends Controller
{
    public function __construct()
    {
        Inertia::share('sidebarLinks', event(new SettingsLinksEvent()));
    }

    public function index()
    {
        $mailings = Mailing::with(['event', 'status'])->get();
        return Inertia::render('@Mailing/Mailing/MailingIndex', [
            'mailings' => $mailings
        ]);
    }

    public function create()
    {
        return Inertia::render('@Mailing/Mailing/MailingCreate', [
            'mailing' => new Mailing(),
            'events' => MailingEvents::with('statuses')->get()
        ]);
    }

    public function store(MailingRequest $request)
    {
        $validated = $request->validated();
        Mailing::create($validated);

        return redirect(route('mailing.index'));
    }

    public function edit(Mailing $mailing)
    {
        $mailing->load(['event','event.statuses', 'status']);
        return Inertia::render('@Mailing/Mailing/MailingEdit', [
            'mailing' => $mailing,
            'events' => MailingEvents::with('statuses')->get()
        ]);
    }

    public function update(MailingRequest $request, Mailing $mailing)
    {
        $validated = $request->validated();
        $mailing->update($validated);

        return redirect(route('mailing.index'));
    }

    public function destroy(Mailing $mailing)
    {
        $mailing->delete();

        return redirect(route('mailing.index'));
    }
}
