<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiLoginsTable extends Migration
{

    public function up()
    {
        Schema::create('api_logins', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['phone', 'email'])->index();
            $table->string('phone')->nullable()->index();
            $table->string('email')->nullable()->index();
            $table->string('code')->index();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('api_logins');
    }
}
