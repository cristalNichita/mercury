<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsTable extends Migration
{
    /**
     * Контактные лица
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();

            // Для двухсторонной синхронизации
            $table->uuid('guid')->nullable()->index();
            $table->uuid('guid_site')->nullable()->index();

            $table->string('name')->nullable();
            $table->string('position')->nullable();

            $table->foreignId('holding_id')
                ->constrained('holdings')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->timestamps();
        });

        Schema::table('holdings', function (Blueprint $table) {
            // Непонятный глюк с foreign key - пока пусть будет индексом
            $table->unsignedBigInteger('contact_id')->nullable()->index();
        });

        Schema::table('users', function (Blueprint $table) {
            // Непонятный глюк с foreign key - пока пусть будет индексом
            $table->unsignedBigInteger('contact_id')->nullable()->index();
        });
    }

    public function down()
    {
        Schema::table('holdings', function (Blueprint $table) {
            $table->dropColumn('contact_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('contact_id');
        });

        Schema::dropIfExists('contacts');
    }
}
