<?php

use Illuminate\Database\Migrations\Migration;
use Modules\User\Database\Seeders\UserDatabaseSeeder;

class SeedUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            $seed = new UserDatabaseSeeder();
            $seed->run();
        } catch (Exception $e) {

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
