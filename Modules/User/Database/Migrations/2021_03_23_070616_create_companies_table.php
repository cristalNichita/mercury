<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();

            $table->foreignId('holding_id')
                ->nullable()
                ->constrained('holdings')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->string('name')->nullable()->index();

            $table->uuid('guid')->nullable()->index();
            $table->uuid('guid_site')->nullable()->index();

            $table->integer('type')->default(0);
            $table->string('type_1c')->nullable();
            $table->string('inn')->nullable()->index();
            $table->string('kpp')->nullable();
            $table->string('ogrn')->nullable();

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
        Schema::dropIfExists('companies');
    }
}
