<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSslExpireSubscriber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriber_ssl_expire', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
            $table->unsignedBigInteger('ssl_id');
            $table->timestamps();

            $table->foreign('ssl_id')->references('id')->on('domain_ssl_details')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscriber_ssl_expire', function (Blueprint $table) {
            $table->dropForeign(['ssl_id']);
        });

        Schema::dropIfExists('subscriber_ssl_expire');
    }
}
