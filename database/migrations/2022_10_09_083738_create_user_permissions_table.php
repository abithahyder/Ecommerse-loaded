<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_permissions', function (Blueprint $table) {
            $table->bigIncrements('up_id');
            $table->string('up_name')->nullable();
            $table->bigInteger('up_related_id');
            $table->string('up_view')->default('0');
            $table->string('up_create')->default('0');
            $table->string('up_edit')->default('0');
            $table->string('up_delete')->default('0');
            $table->string('up_related_type');
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
        Schema::dropIfExists('user_permissions');
    }
}
