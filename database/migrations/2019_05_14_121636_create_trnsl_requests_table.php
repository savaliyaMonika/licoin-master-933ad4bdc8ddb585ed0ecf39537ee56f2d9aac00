<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrnslRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trnsl_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('email');
            $table->string('from_file')->nullable();
            $table->string('to_file')->nullable();
            $table->string('from_lng');
            $table->string('to_lng');
            $table->time('time_to_process')->nullable();
            $table->enum('status', ['requested', 'inprocess', 'processed', 'failed', 'mailfailed'])->default('requested');
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
        Schema::dropIfExists('trnsl_requests');
    }
}
