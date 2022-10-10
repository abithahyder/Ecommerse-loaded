<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdermastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordermasters', function (Blueprint $table) {
            $table->increments('orm_id');
            $table->integer('om_u_id');
            $table->string('om_or_id_no');
            $table->date('om_date');
            $table->string('om_status')->nullable();
            $table->float('om_total')->nullable();
            $table->string('om_discount')->nullable();
            $table->string('om_grand_total')->nullable();
            $table->string('total_items')->nullable();
            $table->string('delivered_items')->nullable();
            $table->string('or_invoice_number');
            $table->string('delivery_charge')->nullable();
            $table->string('payment')->nullable();
            $table->string('om_sname')->nullable();
            $table->tinyText('om_addresline_1')->nullable();
            $table->tinyText('om_addresline_2')->nullable();
            $table->string('om_mobile')->nullable();
            $table->string('om_city');
            $table->string('om_state');
            $table->string('om_country');
            //$table->string('')->nullable();
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
        Schema::dropIfExists('ordermasters');
    }
}
