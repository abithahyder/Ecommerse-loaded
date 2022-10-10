<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_addresses', function (Blueprint $table) {
            $table->increments('ca_id');
            $table->integer('ca_client_id')->nullable();
            $table->text('ca_address_line_1')->nullable();
            $table->text('ca_address_line_2')->nullable();
            $table->string('ca_mobile')->nullable();
            $table->string('ca_alter_num')->nullable();
            $table->string('ca_city')->nullable();
            $table->string('ca_state')->nullable();
            $table->string('ca_country')->nullable();
            $table->string('ca_pincode')->nullable();
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
        Schema::dropIfExists('client_addresses');
    }
}
