<?php

namespace Modules\Site\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Site\Database\factories\InfoBlockFactory;
use Modules\Site\Entities\InfoBlock;

class InfoBlockTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        InfoBlock::factory()->count(rand(2, 5))->create()->make();

        InfoBlock::create([
            'title' => 'Доставка',
            'description' => 'Деловые линии, Байкал-Сервис, GTD (Кит, Кашалот), DPD, Энергия, ПЭК, СДЭК',
            'background_color' => '#F5574E',
            'slug' => 'delivery',
            'in_main' => true
        ]);

        InfoBlock::create([
            'title' => 'Онлайн-оплата',
            'description' => "У нас можно оплатить: \nVISA, Mastercard, Qiwi, Яндекс.Деньги, МИР и другие.",
            'background_color' => '#147586',
            'slug' => 'online-payment',
            'in_main' => true
        ]);

        InfoBlock::create([
            'title' => 'Офлайн магазин',
            'description' => "г. Волгодонск, Степная, 70 \nг. Волгодонск, Ленина, 4 \nг. Краснодар,Речная, 15",
            'background_color' => '#1A3163',
            'slug' => 'offline-shop',
            'in_main' => true
        ]);

        InfoBlock::create([
            'title' => 'Доставка грузов',
            'description' => "по всей Россиии",
            'background_color' => '#FED7BD',
            'slug' => 'delivery-all'
        ]);
    }
}
