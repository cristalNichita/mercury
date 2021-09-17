<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactParamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_params', function (Blueprint $table) {
            $table->id();

            $table->morphs('parent');

            $table->string('type')->default('');
            $table->string('view')->default('');
            $table->string('value')->default('');
            $table->string('value_1c')->nullable();

            $table->index(['type', 'value']);
            $table->index(['parent_type', 'parent_id', 'type', 'value']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contact_params');
    }
}
