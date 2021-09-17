<?php

namespace Modules\User\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\User\Entities\Contact;
use Modules\User\Events\ContactCreated;
use Modules\User\Http\Requests\ApiUserContactRequest;
use Modules\User\Notifications\InviteUserNotification;


class ApiUserContactController extends BaseController
{
    public function index(Request $request)
    {
        /** @var Contact $contact */
        $contact = $request->user()->contact;

        return $contact->holding->contacts()
            ->with('params')
            ->where('id', '!=', $contact->id)
            ->get()
            ->append(['phone', 'email']);
    }

    public function store(ApiUserContactRequest $request)
    {
        /** @var Contact $contact */
        $contact = $request->user()->contact;

        try {

            $contact = Contact::create([
                'name' => $request->name,
                'holding_id' => $contact->holding_id
            ]);
            $contact->phone = $request->phone;
            $contact->email = $request->email;

            // Для выгрузки в 1С
            event(new ContactCreated($contact));

            $password = Str::random(8);
            $user = User::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'contact_id' => $contact->id,
                'password' => Hash::make($password)
            ]);

            $user->notify(new InviteUserNotification($request->user(), $password));

            return $contact;

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    protected function checkAccess($request, $contact)
    {
        // TODO: нужно добавить проверку еще на возможность редактирвоать только
        // TODO: приглашенных тобой пользователей
        // Проверяем принадлежность пользователю к холдингу
        if ($contact->holding->id !== $request->user()->contact->holding->id) {
            throw new \Exception('Forbidden', 403);
        }
    }

    public function destroy(Request $request, Contact $contact)
    {
        $this->checkAccess($request, $contact);

        if ($contact->orders->count()) {
            return $this->sendError('order_exist');
        }

        if ($contact->user) {
            $contact->user->delete();
        }

        $contact->delete();

        return 'success';
    }

    public function update(ApiUserContactRequest $request, Contact $contact)
    {
        $this->checkAccess($request, $contact);

        $contact->name = $request->name;
        $contact->phone = $request->phone;
        $contact->email = $request->email;
        $contact->save();

        return $contact;
    }

}
