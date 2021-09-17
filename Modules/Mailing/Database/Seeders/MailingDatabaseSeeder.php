<?php

namespace Modules\Mailing\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Mailing\Entities\Mailing;
use Modules\Mailing\Entities\MailingEvents;
use Modules\Mailing\Entities\MailingEventStatus;

class MailingDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $events = [
            'Регистрация',
            'Восстановления пароля',
            'Брошенная корзина',
            'Новый заказ',
            [
                'name' => 'Смена статуса заказа',
                'statuses' => [
                    'принят', 'в обработке',
                    'ждет оплаты', 'оплачен',
                    'отправлен', 'доставлен', 'отменен'
                ]
            ],

            [
                'name' => 'Смена статуса оплаты',
                'statuses' => [
                    'выставлен счет', 'ждет оплаты онлайн',
                    'оплачен', 'оплачен частично'
                ]
            ],

            [
                'name' => 'Смена статуса доставки',
                'statuses' => [

                ]
            ],
            [
                'name' => 'Смена статуса рекламации',
                'statuses' => [
                    'принято к рассмотрению', 'отклонена',
                    'рекламация принята', 'исполнено'
                ]
            ],
        ];

        foreach ($events as $event) {
            $tbl_event = MailingEvents::create(['name' => ($event['name'] ?? $event)]);

            foreach ($event['statuses'] ?? [] as $status) {
                MailingEventStatus::create([
                    'name' => $status,
                    'event_id' => $tbl_event->id
                ]);
            }
        }

        $tbl_event = MailingEvents::where('name', 'like', 'Регистрация')->first();

        $mailing = Mailing::create([
            'name' => 'Регистрация пользователя (Админ)',
            'event_id' => $tbl_event->id,
            'mail_template' => '<h3>Шаблон письма регистрации пользователя для админа</h3>',
            'type' => 0
        ]);

        $tbl_event = MailingEvents::where('name', 'like', 'Смена статуса рекламации')->first();
        $tbl_status =  MailingEventStatus::where('event_id', '=', $tbl_event->id)
                                            ->where('name', 'like', 'рекламация принята')->first();


        $mailing = Mailing::create([
            'name' => 'Рекламация принята (Пользователь)',
            'event_id' => $tbl_event->id,
            'status_id' => $tbl_status->id,
            'mail_template' => '<h3>Шаблон письма на статус "Рекламация принята"</h3>',
            'type' => 1
        ]);

    }
}
