<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')
                ->constrained()
                ->cascadeOnUpdate()->cascadeOnDelete();

            $table->string('id_1c')->nullable()->index();
            $table->string('status')->nullable();

            $table->string('name')->nullable();

            $table->decimal('old_price', 15, 2)->default(0);
            $table->decimal('price', 15, 2)->default(0);
            $table->integer('count')->default(1);
            $table->decimal('total', 15, 2)->default(0);

            $table->foreignId('product_id')
                ->nullable()
                ->constrained('products')
                ->nullOnDelete()
                ->cascadeOnUpdate();

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
        Schema::dropIfExists('order_items');
    }
}
