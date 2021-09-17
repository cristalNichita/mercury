<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{

    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->string('password');
            $table->string('phone')->nullable()->unique();
            $table->tinyInteger('role');
            $table->timestamps();

            /**  @deprecated fields*/
            $table->foreignId('current_team_id')->nullable();
            $table->string('lastname')->nullable();
            $table->string('middlename')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('partner')->default(false);
            $table->boolean('active')->default(true);
            $table->string('position')->nullable();
            $table->string('inn')->nullable()->unique();
            $table->string('ogrn')->nullable();
            $table->string('kpp')->nullable();
            $table->string('bik')->nullable();
            $table->boolean('type')->default(0);
            $table->integer('sms_code')->nullable();
            $table->timestamp('sms_created_at')->nullable();
            $table->rememberToken();
            $table->text('profile_photo_path')->nullable();
            $table->string('guid')->nullable()->unique();
            $table->boolean('deleted')->default(false);
            $table->string('position_title')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
