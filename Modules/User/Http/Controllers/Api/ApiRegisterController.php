<?php

namespace Modules\User\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Modules\User\Entities\ApiLogin;
use Modules\User\Entities\Contact;
use Modules\User\Events\ContactCreated;
use Modules\User\Http\Requests\ApiRegisterRequest;
use Modules\User\Notifications\RegisterUserNotification;
use Modules\User\Traits\UserConfirmationTrait;

class ApiRegisterController extends BaseController
{
    use UserConfirmationTrait;

    /**
     * Регистрация покупателя по телефону или Email.
     *
     * @param ApiRegisterRequest $request
     * @return JsonResponse
     */
    public function __invoke(ApiRegisterRequest $request): JsonResponse
    {
        $query = ApiLogin::orderByDesc('id');

        if ($request->type === 'phone') {

            $user = User::findByPhone($request->phone);

            $query->where('phone', Helper::clearPhone($request->phone));
        } else {

            $user = User::findByEmail($request->email);

            if ($user) {
                if (!Hash::check($request->password, $user->password)) {
                    throw ValidationException::withMessages([
                        'email' => 'Пользователь с таким Email уже зарегистрирован',
                    ]);
                } else {
                    $token = $user->createToken('api')->plainTextToken;
                    return $this->sendSuccess([
                        'token' => $token,
                        'data' => $user
                    ]);
                }
            }
            $query->where('email', $request->email);
        }

        if ($request->filled('code')) {

            $login = $query->where('code', $request->input('code'))->first();

            if (!$login) {
                throw ValidationException::withMessages([
                    'code' => 'Неверный код',
                ]);
            }

            if (empty($user)) {
                $user = $this->createUserFromData($request->validated());
            }

            $token = $user->createToken('api')->plainTextToken;

            return $this->sendSuccess(['token' => $token, 'data' => $user]);
        }

        return $this->sendMessageCode($query, $request);
    }

    /**
     * Создание пользователя из массива данных.
     *
     * @param array $data
     * @return User
     */
    protected static function createUserFromData(array $data): User
    {
        $password = $data['password'] ?? Str::random(6);
        $for_create_data = [
            'password' => Hash::make($password),
        ];

        $contact = null;

        if ($data['type'] === 'email') {

            $for_create_data['name'] = $data['email'];
            $for_create_data['email'] = $data['email'];

            $contact = Contact::findByEmail($data['email']);

        } elseif ($data['type'] === 'phone') {

            $phone = Helper::clearPhone($data['phone']);
            $for_create_data['name'] = $phone;
            $for_create_data['phone'] = $phone;

            $contact = Contact::findByPhone($phone);
        }

        if ($contact) {
            $for_create_data['name'] = $contact->name;
        } else {
            $contact = Contact::create(['name' => $for_create_data['name']]);
            $contact->setParam($data['type'], $for_create_data['name']);

            // Для выгрузки в 1С
            event(new ContactCreated($contact));
        }

        $for_create_data['contact_id'] = $contact->id;

        $user = User::create($for_create_data);

        event(new Registered($user));
        $user->notify(new RegisterUserNotification($password, $data['type']));

        return $user;
    }
}
