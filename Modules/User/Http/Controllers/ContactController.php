<?php

namespace Modules\User\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Modules\Order\Transformers\OrderResource;
use Modules\User\Entities\Company;
use Modules\User\Entities\Contact;
use Modules\User\Events\ContactCreated;
use Modules\User\Notifications\InviteUserNotification;
use \Inertia\Response;

class ContactController extends BaseController
{

    public function index(Request $request)
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return Inertia::render('@User/ContactEdit', [
            'contact' => new Contact(),
        ]);
    }


    /**
     * Show the specified resource.
     *
     * @param Request $request
     * @param Contact $contact
     * @return Response
     */
    public function show(Request $request, Contact $contact)
    {
        $contact->load(['params', 'holding', 'user']);

        $orders = $contact->orders()
        ->sort($request->get('sort', 'id-asc'))
        ->paginate($this->per_page);

        return Inertia::render('@User/ContactShow', [
            'contact' => $contact,
            'orders' => OrderResource::collection($orders),
            'inviteUserErrors' => $this->getInviteUserErrors($contact),
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, Company $company)
    {
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $contact = new Contact();
        $contact->name = $request->input('name');
        $contact->position = $request->input('position');
        $contact->holding_id = $request->input('holding_id');

        // чтобы получить id - используется для связанных сущностей
        $contact->save();

        $contact->fill(Arr::except($request->all(), 'holding_id'));
        $contact->save();

        event(new ContactCreated($contact));

        return redirect(route('users.contacts.show', $contact->id));
    }

    /**
     * Пригласить нового пользователя на основе контакта.
     *
     * @param Request $request
     * @param Contact $contact
     * @return RedirectResponse
     */
    public function inviteUser(Request $request, Contact $contact) {

        $errors = $this->getInviteUserErrors($contact);

        if ($errors) {
            return back()->withErrors($errors);
        }

        $password = Str::random(8);

        $user = User::create([
            'name' => $contact->name,
            'phone' => $contact->phone,
            'email' => $contact->email,
            'contact_id' => $contact->id,
            'password' => Hash::make($password)
        ]);

        $user->notify(new InviteUserNotification($request->user(), $password));

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Company $company)
    {
    }

    /**
     * Получает массив ошибок при приглашении пользователя.
     *
     * @param Contact $contact
     * @return array|string[]
     */
    protected function getInviteUserErrors(Contact $contact) {
        $errors = [];

        $fields = ['email', 'phone'];

        $filed_labels = [
            'email' => 'Email',
            'phone' => 'Телефон',
        ];

        $error_format = '%s уже использует для авторизации %s %s';

        foreach ($fields as $field) {
            $field_value = $contact->{$field};

            if (!$field_value) {
                return ["Для приглашения пользователя необходимо заполнить {$filed_labels[$field]}"];
            }

            $user = User::firstWhere($field, $field_value);

            if (!$user) {
                continue;
            }

            $errors[] = sprintf(
                $error_format,
                $user->fullname,
                $filed_labels[$field],
                $field_value
            );
        }

        return $errors;
    }
}
