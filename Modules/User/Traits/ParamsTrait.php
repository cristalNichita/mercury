<?php


namespace Modules\User\Traits;


use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\User\Entities\Contact;
use Modules\User\Entities\ContactParam;

/**
 * Trait ParamsTrait
 * @package Modules\User\Traits
 * @property string $phone Основной телефон (первый)
 * @property string $email Основной Email (первый)
 * @property string $address Основной адрес (первый)
 * @property string $u_address Юридический адрес
 * @property string $f_address Фактический адрес
 */
trait ParamsTrait
{
    /**
     * @return MorphMany
     */
    public function params()
    {
        return $this->morphMany(ContactParam::class, 'parent');
    }

    public function setPhoneAttribute($value)
    {
        $this->setParam('phone', $value);
    }

    public function getPhoneAttribute()
    {
        $param = $this->getParam('phone');
        return $param->value ?? '';
    }

    public function setEmailAttribute($value)
    {
        $this->setParam('email', $value);
    }

    public function getEmailAttribute()
    {
        $param = $this->getParam('email');
        return $param->value ?? '';
    }

    public function getAddressAttribute()
    {
        $param = $this->getParam('address');
        return $param->value ?? '';
    }

    public function setAddressAttribute($value)
    {
        $this->setParam('address', $value);
    }

    public function setUAddressAttribute($value)
    {
        $this->setParam('address', $value, 'Юридический адрес');
    }

    public function setFAddressAttribute($value)
    {
        $this->setParam('address', $value, 'Фактический адрес');
    }

    public function setParam($type, $value, $view = '')
    {
        if (is_array($value)) {
            foreach ($value as $param) {
                $this->$type = $param;
            }
        } elseif (!is_null($value)) {

            if ($type === 'phone') {
                $value = Helper::clearPhone($value);
            }

            // Пусть лучше упадет исключение и программист сразу заметит что он не сохранил контакт
            $this->params()->updateOrCreate(['type' => $type,  'view' => $view], ['value' => $value]);
        }
    }

    public function getParam($type)
    {
        return $this->params()->where('type', $type)->first();
    }

    /**
     * Поиск по телефону и email
     * @param $phone
     * @param $email
     * @return null|Contact
     */
    public static function findByPhoneOrEmail($phone, $email)
    {
        $contact = static::findByPhone($phone);
        if (empty($contact)) {
            $contact = static::findByEmail($email);
        }
        return $contact;
    }

    /**
     * Поиск по телефону
     * @param $phone
     * @return null|Contact
     */
    public static function findByPhone($phone)
    {
        $phone = Helper::clearPhone($phone);
        return static::findByParam('phone', $phone);
    }

    /**
     * Поиск по Email
     * @param $email
     * @return null|Contact
     */
    public static function findByEmail($email)
    {
        return static::findByParam('email', $email);
    }

    /**
     * Поиск по любому параметру
     * @param $type
     * @param $value
     * @return null|Contact
     */
    public static function findByParam($type, $value)
    {
        if (empty($value)) {
            return null;
        }

        $find = ContactParam::where('parent_type', static::class)
            ->where('type', $type)
            ->where('value', $value)->first();
        return $find->parent ?? null;
    }
}
