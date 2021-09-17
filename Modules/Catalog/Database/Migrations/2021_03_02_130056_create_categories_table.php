<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Создаем таблицу категорий
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {

            $table->id();
            $table->string('id_1c')->default('')->index();

            $table->string('title')->default('')->index();
            $table->string('slug')->default('')->index();

            $table->boolean('active')->default(false);
            $table->integer('product_count')->default(0);

            $table->nestedSet();

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
        Schema::dropIfExists('categories');
    }
}
