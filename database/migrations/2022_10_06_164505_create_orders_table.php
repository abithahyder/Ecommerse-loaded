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
            $table->increments('or_id');
            $table->string('or_no');
            $table->string('invoice_number');
            $table->string('qty');
            $table->integer('p_id');
            $table->integer('u_id');
            $table->string('name')->nullable();
            $table->string('oim_image')->nullable();
            $table->string('price')->nullable();
            $table->string('payment_mode')->nullable();
            $table->string('pay_status')->nullable();
            $table->string('delivery_status')->nullable();
            $table->string('payment_date')->nullable();
            $table->string('delivery_date')->nullable();
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
