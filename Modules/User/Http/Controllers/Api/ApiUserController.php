<?php

namespace Modules\User\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Modules\User\Entities\ApiLogin;
use Modules\User\Entities\Contact;
use Modules\User\Events\ContactCreated;
use Modules\User\Http\Requests\ApiConfirmRequest;
use Modules\User\Http\Requests\ApiUserPasswordRequest;
use Modules\User\Http\Requests\ApiUserProfileRequest;
use Modules\User\Traits\UserConfirmationTrait;

class ApiUserController extends BaseController
{
    use UserConfirmationTrait;

    public function profile(ApiUserProfileRequest $request)
    {

        $user = $request->user();

        $phone = Helper::clearPhone($request->phone);
        $email = $request->email;

        try {
            if ($phone !== $user->phone) {
                throw new \Exception('confirm_phone');
            }

            if ($email !== $user->email) {
                throw new \Exception('confirm_email');
            }

            // Крайний случай - так как контакт должен уже быть
            if (!$user->contact) {
                $contact = Contact::create(['name' => $request->name]);
                $user->contact()->assign($contact);

                // Для выгрузки в 1С
                event(new ContactCreated($contact));
            }
            $contact = $user->contact;

            $contact->phone = $phone;
            $contact->email = $email;
            $contact->name = $request->name;
            $contact->save();

            // Очищаем данный телефон у других пользователей
            User::where('phone', $phone)->where('id', '!=', $user->id)->update(['phone' => null]);
            User::where('email', $email)->where('id', '!=', $user->id)->update(['email' => null]);

            $user->phone = $phone;
            $user->email = $email;
            $user->name = $request->name;
            $user->save();

            return ['user' => $user];

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function password(ApiUserPasswordRequest $request)
    {
        $user = $request->user();
        $user->password = Hash::make($request->password);
        $user->save();
        return "ok";
    }

    /**
     * Подтверждение email или телефона пользователя.
     *
     * @param ApiConfirmRequest $request
     * @return JsonResponse
     */
    public function confirm(ApiConfirmRequest $request)
    {
        $query = ApiLogin::orderByDesc('id');

        if ($request->type === 'phone') {
            $query->where('phone', Helper::clearPhone($request->phone));
        } else {
            $query->where('email', $request->email);
        }

        if ($request->filled('code')) {

            $login = $query->where('code', $request->input('code'))->first();

            if (!$login) {
                throw ValidationException::withMessages([
                    'code' => 'Неверный код',
                ]);
            }

            $user = $request->user();

            // Очищаем у других пользователей
            if ($request->type === 'phone') {
                $phone = Helper::clearPhone($request->phone);
                User::where('phone', $phone)
                    ->where('id', '!=', $user->id)
                    ->update(['phone' => null]);

                $user->phone = $phone;
                $user->save();
                return $this->sendSuccess([
                        'message' => 'Телефон успешно подтвержден',
                        'phone' => $user->phone,
                    ]
                );
            } else {
                $email = $request->email;
                User::where('email', $email)
                    ->where('id', '!=', $user->id)
                    ->update(['email' => null]);

                $user->email = $email;
                $user->save();
                return $this->sendSuccess([
                        'message' => 'Email успешно подтвержден',
                        'phone' => $email,
                    ]
                );
            }
        }

        return $this->sendMessageCode($query, $request);
    }

    /**
     * Аутентификация существующего пользователя
     * при помощи подтверждения email или телефона.
     *
     * @param ApiConfirmRequest $request
     * @return JsonResponse
     */
    public function loginConfirm(ApiConfirmRequest $request)
    {
        $query = ApiLogin::orderByDesc('id');

        if ($request->type === 'phone') {
            $query->where('phone', Helper::clearPhone($request->phone));

            $user = User::findByPhone($request->phone);
        } else {
            $query->where('email', $request->email);

            $user = User::findByEmail($request->email);
        }

        if ($request->filled('code')) {

            $login = $query->where('code', $request->input('code'))->first();

            if (!$login) {
                throw ValidationException::withMessages([
                    'code' => 'Неверный код',
                ]);
            }

            if (empty($user)) {
                throw ValidationException::withMessages([
                    $request->type => 'Пользователь не найден',
                ]);
            }

            $token = $user->createToken('api')->plainTextToken;

            return $this->sendSuccess(['token' => $token, 'data' => $user]);
        }

        return $this->sendMessageCode($query, $request);
    }

}
