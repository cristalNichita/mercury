<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Settings\Database\Seeders\GlobalDirectoryTableSeeder;

class CreateGlobalDirectoryItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('global_directory_items', function (Blueprint $table) {
            $table->id();

            $table->text('name');
            $table->json('additions')->nullable();

            $table->foreignId('directory_id')
                ->constrained('global_directories', 'id')
                ->cascadeOnDelete();

        });

        $seed = new GlobalDirectoryTableSeeder();
        $seed->run();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('global_directory_items');
    }
}
