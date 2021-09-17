<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Site\Database\Seeders\InfoBlockTableSeeder;

class CreateInfoBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('info_blocks', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('description');
            $table->char('background_color', 7)->nullable();
            $table->string('slug')->nullable();
            $table->boolean('in_main')->default(0);
            $table->timestamps();

        });

        $seeder = new InfoBlockTableSeeder();
        $seeder->run();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('info_blocks');
    }
}
