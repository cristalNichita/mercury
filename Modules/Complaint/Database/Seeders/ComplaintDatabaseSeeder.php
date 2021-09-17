<?php

namespace Modules\Complaint\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComplaintDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 1;$i < 40;$i++) {
            DB::table('complaints')->insert([
                'user_id' => rand(1,3),
                'order_id' => rand(1,10),
                'status_id' => rand(1,3),
                'type_id' => rand(1,3),
                'description' => 'Возврат...',
                'comment' => 'Несоответствие заказу...',
                'created_at' => Carbon::now()->subDays(rand(1,1000))->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->subDays(rand(1,1000))->format('Y-m-d H:i:s'),
            ]);
        }
    }
}
