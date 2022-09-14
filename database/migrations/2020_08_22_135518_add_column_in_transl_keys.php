<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnInTranslKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trnsl_keys', function (Blueprint $table) {
            $table->string('api_key_type')->nullable();
            $table->boolean('status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trnsl_keys', function (Blueprint $table) {
            $table->dropColumn('api_key_type');
            $table->dropColumn('status');
        });
    }
}
