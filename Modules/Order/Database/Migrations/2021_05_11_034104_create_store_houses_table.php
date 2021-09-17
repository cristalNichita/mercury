<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreHousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_houses', function (Blueprint $table) {
            $table->id();

            $table->string('name')->nullable();
            $table->string('address');
            $table->boolean('active')->default(false);
            $table->integer('sort')->default(500);
            $table->string('lat')->nullable();
            $table->string('long')->nullable();

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
        Schema::dropIfExists('store_houses');
    }
}
