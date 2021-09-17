<?php

namespace Modules\User\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Modules\User\Http\Requests\ApiLoginRequest;

class ApiLoginController extends BaseController
{
    /**
     * Аутентификация  по email или телефону и паролю.
     *
     * @param ApiLoginRequest $request
     * @return array|JsonResponse|string[]
     *
     * @throws ValidationException
     */
    public function __invoke(ApiLoginRequest $request)
    {
        if ($request->type === 'email') {
            $user = User::findByEmail($request->email);
        } else {
            $user = User::findByPhone($request->phone);
        }

        if (!$user) {
            throw ValidationException::withMessages(
                [$request->type => 'Пользователь не зарегистрирован']
            );
        }

        if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages(
                ['password' => 'Неверный пароль']
            );
        }

        $token = $user->createToken('api')->plainTextToken;

        return $this->sendSuccess([
            'token' => $token,
            'user' => $user
        ]);
    }
}
