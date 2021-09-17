<?php

use Illuminate\Database\Migrations\Migration;
use Modules\Mailing\Database\Seeders\MailingDatabaseSeeder;

class SeedMailing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            $seed = new MailingDatabaseSeeder();
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
        //
    }
}
