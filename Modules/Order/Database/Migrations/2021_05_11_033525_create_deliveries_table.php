<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('code')->unique();
            $table->string('delivery_class');
            $table->boolean('active')->default(false);
            $table->integer('sort')->default(500);

            $table->timestamps();
        });

        \Illuminate\Support\Facades\DB::table('deliveries')->insert([
            'name' => 'Сдэк',
            'code' => 'cdek',
            'active' => true,
            'sort' => 500,
            'delivery_class' => \Modules\Order\Classes\CdekService::class
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deliveries');
    }
}
