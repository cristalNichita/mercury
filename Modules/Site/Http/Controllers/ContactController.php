<?php

namespace Modules\Site\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Modules\Settings\Dict\DictFacade;
use Modules\Settings\Entities\GlobalDirectoryItem;
use Modules\Site\Http\Requests\ContactRequest;

class ContactController extends Controller
{
    const DICT_NAME = 'contacts-page';

    public function index()
    {
        $contacts = DictFacade::get(self::DICT_NAME);
        return Inertia::render('Site/Contact/ContactIndex', [
            'contacts' => $contacts
        ]);
    }

   public function create()
    {
        return Inertia::render('Site/Contact/ContactCreate', [
            'contact' => new GlobalDirectoryItem()
        ]);
    }

    public function store(ContactRequest $request)
    {
        DictFacade::addItem(self::DICT_NAME, $request->validated());

        return redirect(route('site.contacts'));
    }

    public function edit($contact)
    {
        $contact = DictFacade::getItem($contact);
        return Inertia::render('Site/Contact/ContactEdit', [
            'contact' => $contact
        ]);
    }

    public function update(ContactRequest $request, $contact)
    {
        if (DictFacade::hasItem(self::DICT_NAME, $contact)) {
            DictFacade::updateItem($contact, $request->validated());
        }

        return redirect(route('site.contacts'));
    }

    public function destroy($contact)
    {
        if (DictFacade::hasItem(self::DICT_NAME, $contact)) {
            DictFacade::deleteItem($contact);
        }

        return redirect(route('site.contacts'));
    }
}
