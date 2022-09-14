<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSslcheckSiteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('domain_ssl_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('web_site');
            $table->string('domain_name')->nullable()->default(null);
            $table->string('server_ip_address')->nullable()->default(null);
            $table->string('issuer')->nullable()->default(null);
            $table->string('signature_algorithm')->nullable()->default(null);
            $table->longText('additional_domains')->nullable()->default(null);
            $table->mediumText('fingerprint')->nullable()->default(null);
            $table->mediumText('fingerprint_sha256')->nullable()->default(null);
            $table->dateTime('valid_from_date')->nullable()->default(null);
            $table->dateTime('expiration_date')->nullable()->default(null);
            $table->boolean('is_valid')->default(false);
            $table->boolean('is_expired')->default(false);
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
        Schema::dropIfExists('domain_ssl_details');
    }
}
