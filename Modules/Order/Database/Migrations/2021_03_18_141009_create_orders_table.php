<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->uuid('guid')->nullable()->index();
            $table->uuid('guid_site')->nullable()->index();

            $table->string('code')->nullable()->index();
            $table->string('status')->default('');

            $table->unsignedBigInteger('contact_id')->nullable()->index();
            $table->unsignedBigInteger('company_id')->nullable()->index();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->decimal('price', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);

            $table->decimal('delivery_cost', 15, 2)->default(0);

            $table->string('payment_type')->default('base');
            $table->boolean('is_payment')->default(false);
            $table->boolean('may_payment')->default(false);

            $table->json('params')->nullable();

            $table->text('comment')->nullable();

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
        Schema::dropIfExists('orders');
    }
}
