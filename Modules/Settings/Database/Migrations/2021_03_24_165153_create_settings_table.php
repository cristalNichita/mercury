<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Settings\Database\Seeders\SettingsTableSeeder;

class CreateSettingsTable extends Migration
{
    /**
     * Таблица для хранения настроек
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->text('value')->nullable();
        });

        $seeder = new SettingsTableSeeder();
        $seeder->run();
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
