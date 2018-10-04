<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('api_client_id')->unsigned()->nullable();
            $table->foreign('api_client_id')->references('id')->on('api_clients');
            $table->mediumText('request')->nullable();
            $table->mediumText('response')->nullable();
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
        Schema::dropIfExists('api_requests');
    }
}
