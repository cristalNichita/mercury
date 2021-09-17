<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHoldingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('holdings', function (Blueprint $table) {
            $table->id();

            // Если холдинг создается на сайте - у него нет id_1c
            $table->string('id_1c')->nullable()->index();
            $table->uuid('guid_site')->nullable()->index();

            $table->string('name')->nullable();

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
//        Schema::table('holding_companies', function (Blueprint $table) {
//            $table->dropForeign(['contact_person_id']);
//        });

        Schema::dropIfExists('holdings');
    }
}

