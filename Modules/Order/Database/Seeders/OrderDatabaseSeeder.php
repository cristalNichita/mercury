<?php

namespace Modules\Order\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $notes = [
            "Задача организации, в особенности же рамки и место обучения кадров",
            "Не следует, однако забывать, что дальнейшее развитие различных форм",
            "Разнообразный и богатый опыт постоянное информационно-пропагандистское",
            "Товарищи! реализация намеченных плановых заданий позволяет оценить значение",
            "Равным образом укрепление и развитие структуры в значительной степени",
        ];
        for($i = 1;$i < 40;$i++) {
            DB::table('orders')->insert([
                'user_id' => 4,
                'order_status' => rand(1,4),
                'name' => 'Иван',
                'email' => 'wegwge@Mail.ru',
                'city' => 'Тюмень',
                'phone_number' => '79995493193',
                'notes' => $notes[rand(0,4)],
                'created_at' => Carbon::now()->subDays(rand(1,1000))->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->subDays(rand(1,1000))->format('Y-m-d H:i:s'),
            ]);
            for($i2 = 1;$i2 < 5;$i2++) {
                DB::table('order_items')->insert([
                    'order_id' => $i,
                    'product' => $i2,
                    'sum' => rand(1,40),
                    'price' => rand(1000,100000),
                    'created_at' => Carbon::now()->subDays(rand(1,1000))->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->subDays(rand(1,1000))->format('Y-m-d H:i:s'),
                ]);
            }
        }

        // $this->call("OthersTableSeeder");
    }
}
