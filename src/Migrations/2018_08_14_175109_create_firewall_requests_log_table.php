<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateFirewallRequestsLogTable
 */
class CreateFirewallRequestsLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('firewall_requests_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('path', 25);
            $table->string('method', 25);
            $table->string('url', 250);
            $table->string('uri', 250);
            $table->string('full_url', 250);
            $table->longText('query');
            $table->string('file_name', 250);
            $table->string('http_host', 250);
            $table->string('http_user_agent', 250);
            $table->string('ip_address', 25);
            $table->longText('all_request_data')->nullable();
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
        Schema::dropIfExists('firewall_requests_log');
    }
}
