<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SmtpSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smtp_setting', function (Blueprint $table) {
            $table->increments('ss_id');
            $table->string('ss_mailer')->nullable();
            $table->string('ss_host')->nullable();
            $table->double('ss_port')->nullable();
            $table->string('ss_uname')->nullable();
            $table->string('ss_pwd')->nullable();
            $table->string('ss_encryption')->nullable();
            $table->string('ss_from_address')->nullable();
            $table->string('ss_from_name')->nullable();
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
        Schema::dropIfExists('smtp_setting');
    }
}
