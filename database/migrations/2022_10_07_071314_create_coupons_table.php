<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->increments('cm_id');
            $table->string('cm_title')->nullable();
            $table->string('cm_code')->nullable();
            $table->string('cm_description')->nullable();
            $table->date('cm_start_date')->nullable();
            $table->date('cm_expiry_date')->nullable();
            $table->float('cm_amount')->nullable();
            $table->string('cm_discount_type')->nullable();
            $table->integer('cm_usage_count')->nullable();
            $table->integer('cm_usage_limit')->nullable();
            $table->string('cm_free_shipping')->nullable();
            $table->float('cm_minimum_amount')->nullable();
            $table->float('cm_maximum_amount')->nullable();
            $table->string('cm_status')->nullable();
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
        Schema::dropIfExists('coupons');
    }
}
