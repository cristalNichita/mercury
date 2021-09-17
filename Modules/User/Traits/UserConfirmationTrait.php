<?php

namespace Modules\User\Traits;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Modules\User\Entities\ApiLogin;
use Modules\User\Http\Requests\ApiConfirmRequest;
use Modules\User\Http\Requests\ApiRegisterRequest;
use Modules\User\Notifications\SendCode;

trait UserConfirmationTrait
{
    /**
     * Отправка кода для подтверждения телефона или email.
     *
     * @param Builder $query
     * @param ApiConfirmRequest|ApiRegisterRequest $request
     * @return JsonResponse
     */
    protected function sendMessageCode(Builder $query, FormRequest $request)
    {
        /** @var $last_login ApiLogin */
        $last_login = $query->first();

        $count_down = (int)env("SMS_COUNTDOWN") ?: 60;
        $now = now();

        if ($last_login && $last_login->isAwait($count_down, $now)) {

            $diff = $last_login->awaitSeconds($count_down, $now);

            return response()->json([
                'message' => 'До повторной отправки осталось',
                'countdown' => $diff,
            ]);
        }

        $code = random_int(100000, 999999);

        $data = [
            'type' => $request->type,
            'code' => $code,
        ];

        if ($request->type === 'phone') {

            $valid_phone = Helper::clearPhone($request->phone);
            $data['phone'] = $valid_phone;
            $message = 'Код отправлен вам по смс';

        } else {

            $data['email'] = $request->email;
            $message = 'Код отправлен вам на электронную почту';

        }

        $login = ApiLogin::create($data);
        $login->notify(new SendCode($login->code, $request->type));

        return response()->json([
            'message' => $message,
            'countdown' => $count_down,
            //Todo: удалить на prod
            'for_dev' => $code,
        ]);
    }
}
