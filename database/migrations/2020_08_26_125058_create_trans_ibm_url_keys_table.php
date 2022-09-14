<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransIbmUrlKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trans_ibm_url_keys', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('trans_key_id');
            $table->foreign('trans_key_id')->references('id')->on('trnsl_keys')->onDelete('cascade');
            $table->string('url_key');
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
        Schema::table('trans_ibm_url_keys', function (Blueprint $table) {
            $table->dropForeign(['trans_key_id']);
        });
        Schema::dropIfExists('trans_ibm_url_keys');
    }
}
