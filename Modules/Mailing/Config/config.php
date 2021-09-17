<?php

use Modules\Mailing\Listeners\MailingEventSubscriber;

return [
    'name' => 'Mailing',
    // TODO Упростить конфигурацию событий и соответствкнно изменить их регистрацию в MailingEventSubscriber::subscribe
    'subscribe' => [
//        'Регистрация' => [
//            \Illuminate\Auth\Events\Registered::class => [MailingEventSubscriber::class, 'handle'],
//        ],
//        'Восстановления пароля' => [
//            \Illuminate\Auth\Events\PasswordReset::class => [MailingEventSubscriber::class, 'handle']
//        ],
//        'Брошенная корзина' => [
//
//        ],
//        'Новый заказ' => [
//            \Modules\Order\Events\OrderCreated::class => [MailingEventSubscriber::class, 'handle']
//        ],
//        'Смена статуса заказа' => [
//            \Modules\Order\Events\OrderChangeStatus::class => [MailingEventSubscriber::class, 'handle']
//        ],
//        'Смена статуса оплаты' => [
//
//        ],
//        'Смена статуса доставки' => [
//
//        ],
        'Статус рекламации' => [
            \Modules\Complaint\Events\ComplaintChangeStatus::class => [MailingEventSubscriber::class, 'handle']
        ],


    ]
];
