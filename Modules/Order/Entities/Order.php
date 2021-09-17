<?php

namespace Modules\Order\Entities;

use App\Models\User;
use App\Traits\SortableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Modules\Order\Classes\OrderInvoicePayment;
use Modules\Order\Classes\OrderOnlinePayment;
use Modules\User\Entities\Company;
use Modules\User\Entities\Contact;
use Modules\User\Entities\Holding;

/**
 * Модель заказа
 * @package Modules\Order\Entities
 *
 * @property string $status Статус заказа (new, process, shipped, finish, cancel)
 * @property string $code Код заказа
 * @property double $price Стоимость товаров
 * @property double $discount Скидка
 * @property double $delivery_cost Стоимость доставки
 * @property-read double $total Итоговая стоимость = price - discount + delivery_cost
 * @property int|null $user_id Пользователь оформивший заказ
 * @property string $comment Комментарий пользователя к заказу
 * @property array $params Параметры заказа
 * @property boolean $is_payment Заказ полностью оплачен
 * @property boolean $may_payment Покупатель может оплачивать заказ
 * @property OrderPayment $payment Оплата заказа
 * @property-read Contact $contact Контактное лицо
 * @property-read Company $company Контрагент
 */
class Order extends Model
{
    use SortableTrait;

    const STATUSES = [
        'new' => 'Принят',
        'process' => 'В обработке',
        'payment_waiting' => 'Ждет оплаты',
        'payment_success' => 'Оплачен',
        'shipped' => 'Отправлен',
        'shipped_success' => 'Доставлен',
        'finish' => 'Завершен',
        'cancel' => 'Отменен',
    ];

    protected $fillable = [

        'status',
        'code', // Код в 1С  - вид ЦТУТ-000024, код на сайте - вид САЙТ-000024

        'guid',
        'guid_site',

        'contact_id',
        'company_id',
        'user_id',   // пользователь оформивший заказ (необязательно)

        'price',    // Стоимость товаров в заказе
        'discount',  // Скидка
        'delivery_cost', //

        'is_payment',   // Заказ оплачен
        'may_payment',  // Заказ можно оплачивать

        'params',

        'comment',

    ];

    protected $casts = [
        'params' => 'array'
    ];

    protected $appends = ['total'];

    protected $with = ['items'];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function fillFromCart($cart)
    {
        $this->price = $cart->total_price;
        foreach ($cart->items as $item) {
            $this->items()->create([
                'name' => $item->name,
                'old_price' => $item->old_price,
                'price' => $item->price,
                'count' => $item->count ?? 1,
                'total_price' => $item->total_price,
                'product_id' => $item->product_id,
            ]);
        }
        $this->load('items');
    }

    /**
     * Итоговая сумма заказа
     */
    public function getTotalAttribute()
    {
        return $this->price - $this->discount + $this->delivery_cost;
    }

    public function payment()
    {
        return $this->hasOne(OrderPayment::class);
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->guid_site = Str::uuid();
        });
    }

}
