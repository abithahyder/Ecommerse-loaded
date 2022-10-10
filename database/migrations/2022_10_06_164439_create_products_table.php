<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('p_id');
            $table->integer('p_cat_parent_id')->default(0);
            $table->integer('p_cat_id')->default(0);
            $table->string('p_name')->nullable();
            $table->text('p_short_desc')->nullable();
            $table->text('p_desc')->nullable();
            $table->float('p_price')->nullable();
            $table->float('p_sale_price')->nullable();
            $table->string('p_status')->nullable();
            $table->string('p_stock')->nullable();
            $table->string('p_image')->nullable();
            $table->string('p_multi_option')->default(0);
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
        Schema::dropIfExists('products');
    }
}
