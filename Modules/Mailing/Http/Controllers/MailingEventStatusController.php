<?php

namespace Modules\Mailing\Http\Controllers;

use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Modules\Mailing\Entities\MailingEvents;
use Modules\Mailing\Entities\MailingEventStatus;
use Modules\Mailing\Http\Requests\MailingEventStatusRequest;

class MailingEventStatusController extends Controller
{
    public function create(MailingEvents $event)
    {
        return Inertia::render('@Mailing/MailingEventStatus/MailingEventStatusCreate',[
            'event' => $event,
            'statuses' => $event->statuses()->get()
        ]);
    }

    public function store(MailingEventStatusRequest $request, MailingEvents $event)
    {
        $validated = $request->validated();
        $event->statuses()->save(new MailingEventStatus($validated));

        return redirect(route('events.edit', $event->id));
    }

    public function edit(MailingEvents $event, MailingEventStatus $status)
    {
        abort_if($event->id != $status->event_id, 403);

        return Inertia::render('@Mailing/MailingEventStatus/MailingEventStatusEdit',[
            'event' => $event,
            'status' => $status,
            'statuses' => $event->statuses()->get()
        ]);
    }

    public function update(MailingEventStatusRequest $request, MailingEvents $event, MailingEventStatus $status)
    {
        $validated = $request->validated();
        $status->update($validated);

        return redirect(route('events.edit', $event->id));
    }

    public function destroy(MailingEvents $event, MailingEventStatus $status)
    {
        $status->delete();

        return redirect(route('events.edit', $event->id));
    }
}
