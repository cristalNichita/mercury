<?php

namespace Modules\User\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class UsedInUserRule implements Rule
{

    protected $ignore_user;

    public function __construct($ignore_user = null)
    {
        $this->ignore_user = $ignore_user;
    }

    public function passes($attribute, $value)
    {

        $find = User::findByParam($attribute, $value);

        if (!$find) {
            return true;
        }

        if ($this->ignore_user) {
            return ($find->id == $this->ignore_user->id);
        }

        return false;
    }

    public function message()
    {
        return 'Уже используется в системе';
    }
}
