<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkuValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sku-values', function (Blueprint $table) {
            $table->increments('skuv_id');
            $table->integer('skuv_sku_id');
            $table->integer('skuv_p_id');
            $table->integer('skuv_v_id');
            $table->integer('skuv_vo_id');
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
        Schema::dropIfExists('sku-values');
    }
}
