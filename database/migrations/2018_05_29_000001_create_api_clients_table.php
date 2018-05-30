<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_clients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('api_key')->unique();
            $table->text('public_key')->nullable();
            $table->dateTime('last_activity')->nullable();
            $table->timestamps();
        });

        Schema::create('api_client_user', function (Blueprint $table) {
            $table->integer('api_client_id')->unsigned();
            $table->foreign('api_client_id')->references('id')->on('api_clients');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_client_user');
        Schema::dropIfExists('api_clients');
    }
}
