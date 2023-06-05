<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->integer('user_id');
            $table->integer('hall_id');
            $table->integer('table_number');
            $table->integer('total_price');
            $table->string('order_status');
            $table->string('card_number',16);
            $table->integer('card_mount');
            $table->integer('card_year');
            $table->integer('card_ccv');
            $table->integer('tips')->nullable();
            $table->integer('payment_sum');




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
