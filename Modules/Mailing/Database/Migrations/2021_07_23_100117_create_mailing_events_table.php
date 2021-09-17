<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailingEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mailing_events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('handling')->default('{}');
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
//        \Illuminate\Support\Facades\DB::raw('SET FOREIGN_KEY_CHECKS=0');
        Schema::dropIfExists('mailing_events');
    }
}
