<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Site\Database\Seeders\PageTableSeeder;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('slug')->nullable();
            $table->mediumText('content');
            $table->integer('type');
            $table->integer('category')->nullable();
            $table->string('author')->nullable();
            $table->boolean('active')->default(false);

            $table->dateTime('published_at')->nullable();
            $table->timestamps();

            $table->unique(['slug', 'type'], 'idx_unique_slug_type');
        });

        $seeder = new PageTableSeeder();
        $seeder->run();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
}
