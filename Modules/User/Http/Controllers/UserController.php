<?php

namespace Modules\User\Http\Controllers;

use App\Helpers\Helper;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Inertia\Response;
use Modules\User\Entities\Contact;
use Modules\User\Events\ContactCreated;
use Modules\User\Http\Requests\CreateUserRequest;

class UserController extends BaseController
{
    /**
     * Список всех пользователей.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $role = $request->get('role');

        if ($role) {
            $users = $users->where('role', $role);
        }

        $users = User::sort($request->get('sort', 'id-asc'))
            ->paginate($this->per_page);

        return Inertia::render('@User/Users', [
            'users' => JsonResource::collection($users),
            'sort' => $request->input('sort'),
        ]);
    }

    /**
     * Форма для создания пользователя.
     *
     * @return Response
     */
    public function create()
    {
        $roles = User::ROLES;

        return Inertia::render('@User/UserCreate', [
            'roles' => $roles,
        ]);
    }

    /**
     * Сохранение нового пользователя.
     *
     * @param CreateUserRequest $request
     * @return RedirectResponse
     */
    public function store(CreateUserRequest $request)
    {
        $data = $request->input();
        $data['password'] = Hash::make($data['password']);

        $phone = Helper::clearPhone($data['phone']);
        $email = $data['email'];

        if (User::firstWhere('phone', $phone)) {
            return back()->withErrors('Номер телефона уже занят');
        }

        if (User::firstWhere('email', $email)) {
            return back()->withErrors('E-Mail уже занят');
        }

        $contact = $email ? Contact::findByEmail($email) : false;

        $contact = $contact ?: Contact::findByPhone($phone);

        DB::beginTransaction();

        if (!$contact) {
            $contact = Contact::create(['name' => $data['name']]);
            $contact->setParam('phone', $phone);
            $contact->setParam('email', $email);
        }

        $data['contact_id'] = $contact->id;

        try {
            User::create($data);
        } catch (ValidationException $e) {
            DB::rollBack();
            return back()->withErrors($e->errors());
        }

        // Для выгрузки в 1С
        event(new ContactCreated($contact));

        DB::commit();
        return redirect()->route('users');
    }

    /**
     * Форма изменения пользователя.
     *
     * @param User $user
     * @return Response
     */
    public function edit(User $user)
    {
        return Inertia::render('@User/UserEdit', [
            'editableUser' => $user,
            'roles' => User::ROLES,
        ]);
    }

    /**
     * Обновить существующего пользователя.
     *
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $data = $request->input();

        $phone = Helper::clearPhone($data['phone']);

        if (User::firstWhere('phone', $phone) && $user->phone != $phone) {
            return back()->withErrors('Номер телефона занят');
        }

        if (User::firstWhere('email', $data['email']) && $user->email != $data['email']) {
            return back()->withErrors('E-Mail занят');
        }

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);
        return back();
    }

    /**
     * Удалить существующего пользователя.
     *
     * @param User $user
     * @return RedirectResponse
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users');
    }
}
