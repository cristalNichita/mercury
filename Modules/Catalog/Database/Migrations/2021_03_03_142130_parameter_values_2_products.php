<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ParameterValues2Products extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parameter_values_2_products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            $table->foreignId('parameter_value_id')
                ->constrained('parameter_values')
                ->cascadeOnDelete();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('parameter_values_2_products', function (Blueprint $table) {

            $table->dropForeign(['product_id']);
            $table->dropForeign(['parameter_value_id']);

        });
        Schema::dropIfExists('parameter_values_2_products');
    }
}
