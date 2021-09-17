<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {

            $table->id();
            $table->string('id_1c')->default('')->unique();
            $table->string('article')->default('')->index();
            $table->string('slug')->default('')->index();

            $table->string('title')->default('')->index();
            $table->text('description')->nullable();
            $table->mediumText('content')->nullable();

            $table->float('price')->default(0)->index();
            $table->float('old_price')->default(0)->index();
            $table->integer('quantity')->default(0)->index();
            $table->integer('quantity_main')->default(0);
            $table->integer('quantity_remote')->default(0);

            $table->float('weight')->default(0);
            $table->integer('volume')->default(0);

            $table->boolean('is_sale')->default(false);
            $table->boolean('is_offer')->default(false);
            $table->boolean('is_new')->default(false);
            $table->boolean('deleted')->default(false);

            $table->float('rating')->nullable();

            $table->foreignId('brand_id')
                ->nullable()
                ->constrained('brands')
                ->onUpdate('cascade')
                ->onDelete('set null');

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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::table('products', function (Blueprint $table) {

            $table->dropForeign(['brand_id']);

        });
        Schema::dropIfExists('products');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
