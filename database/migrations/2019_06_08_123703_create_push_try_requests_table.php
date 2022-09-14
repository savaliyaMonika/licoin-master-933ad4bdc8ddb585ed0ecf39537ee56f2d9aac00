<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePushTryRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('push_try_requests', function (Blueprint $table) {
          $table->increments('id');
          $table->string('devicetokens')->nullable();
          $table->string('message')->nullable();
          $table->string('keyid')->nullable();
          $table->string('teamid')->nullable();
          $table->string('appid')->nullable();
          $table->enum('isproduction',['yes','no'])->nullable()->default('no');
          $table->string('file_cert')->nullable();
          $table->softDeletes();
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
        Schema::dropIfExists('push_try_requests');
    }
}
