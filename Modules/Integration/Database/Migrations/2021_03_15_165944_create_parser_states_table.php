<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParserStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('parser_states');

        Schema::create('integration_logs', function (Blueprint $table) {
            $table->id();

            $table->string('title')->nullable()->index();
            $table->enum('status', ['success', 'error', 'process'])->default('success')->index();
            $table->text('exception')->nullable();
            $table->string('log_path')->nullable();
            $table->string('original_path')->nullable();
            $table->string('new_path')->nullable();
            $table->string('original_name')->nullable();
            $table->string('new_name')->nullable();


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
        Schema::dropIfExists('integration_logs');
    }
}
