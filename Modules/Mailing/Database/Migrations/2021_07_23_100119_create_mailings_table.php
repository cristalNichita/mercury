<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mailings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('event_id')
                ->nullable()
                ->constrained('mailing_events')
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('status_id')
                ->nullable()
                ->constrained('mailing_event_statuses')
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->text('mail_template');
            $table->tinyInteger('type')->default(0);
            $table->json('params')->nullable();

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
        Schema::dropIfExists('mailings');
    }
}
