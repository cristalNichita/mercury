<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();

            // TODO: Заменить на foreignId
            $table->uuid('cart_id')->nullable()->index();

            // TODO: Заменить на foreignId
            $table->unsignedBigInteger('product_id')->nullable()->index();

            $table->string('name')->default('');
            $table->string('article')->default('');

            $table->decimal('old_price', 15, 2)->default(0);
            $table->decimal('price', 15, 2)->default(0);
            $table->integer('count')->default(1);

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
        Schema::dropIfExists('cart_items');
    }
}
