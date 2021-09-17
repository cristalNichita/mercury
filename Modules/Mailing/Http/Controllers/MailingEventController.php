<?php

namespace Modules\Mailing\Http\Controllers;

use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Modules\Mailing\Entities\MailingEvents;
use Modules\Mailing\Helpers\MailingHelper;
use Modules\Mailing\Http\Requests\MailingEventRequest;
use Modules\Settings\Events\SettingsLinksEvent;

class MailingEventController extends Controller
{
    public function __construct()
    {
        Inertia::share('sidebarLinks', event(new SettingsLinksEvent()));
    }

    public function index()
    {
        $events = MailingEvents::with('statuses')->get();

        return Inertia::render('@Mailing/MailingEvent/MailingEventIndex', [
            'events' => $events
        ]);
    }

    public function create()
    {
        $handlings = MailingHelper::getConfigEvent();

        return Inertia::render('@Mailing/MailingEvent/MailingEventCreate', [
            'event' => new MailingEvents(),
            'handlings' => $handlings
        ]);
    }

    public function store(MailingEventRequest $request)
    {
        $validated = $request->validated();
        MailingEvents::create($validated);

        return redirect(route('events.index'));
    }

    public function edit(MailingEvents $event)
    {
        $event->load('statuses');
        $handlings = MailingHelper::getConfigEvent();

        return Inertia::render('@Mailing/MailingEvent/MailingEventEdit', [
            'event' => $event,
            'handlings' => $handlings
        ]);
    }

    public function update(MailingEventRequest $request, MailingEvents $event)
    {
        $validated = $request->validated();
        $event->update($validated);

        return redirect(route('events.index'));
    }

    public function destroy(MailingEvents $event)
    {
        $event->delete();

        return redirect(route('events.index'));
    }
}
