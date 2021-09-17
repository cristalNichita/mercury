<?php

namespace Modules\User\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class CheckActiveUser implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $user = User::firstWhere('email', $value);
        if($user) {
            $active = $user->active;
            return $active ? true : false;
        }else{
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Пользователь заблокирован';
    }
}
