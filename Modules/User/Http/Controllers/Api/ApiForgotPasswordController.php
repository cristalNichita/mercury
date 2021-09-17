<?php

namespace Modules\User\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ApiForgotPasswordController extends BaseController
{

    /**
     * Отправка ссылки для востановления паролья по email.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::firstWhere('email', $request->email);

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'Пользователь с таким Email не найден',
            ]);
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_THROTTLED) {
            return $this->sendError('Слишком много попыток, повторите запрос позже');
        }

        return $this->sendSuccess([
            'message' => 'Ссылка для восстановления пароля отправлена на ваш email'
        ]);
    }

    /**
     * Изменение забытого пароля с проверкой по токену.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
                event(new PasswordReset($user));
            }
        );

        if ($status === Password::INVALID_TOKEN) {
            return $this->sendSuccess([
                'message' => 'Ссылка устарела, запросите восстановление пароля еще раз'
            ]);
        }

        return $this->sendSuccess([
            'message' => 'Пароль успешно изменен'
        ]);
    }
}
