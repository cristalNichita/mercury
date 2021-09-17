<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete()
                ->cascadeOnUpdate();

            // Параметры - для брошенных корзин
            $table->json('params')->nullable();

//            $table->string('name')->default('');
//            $table->string('last_name')->default('');
//            $table->string('phone')->default('');
//            $table->string('city')->default('');
//            $table->text('comment')->default('');

//            $table->string('delivery_type')->default('');
//            $table->json('delivery_params')->nullable();
//            $table->float('delivery_price')->default(0);

//            $table->string('payment_type')->default('');
//            $table->float('payment_price')->default(0);

//            $table->string('discount')->default('');
//            $table->float('discount_price')->default(0);

            // $table->decimal('price', 15, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
}
