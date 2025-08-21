<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('test_results', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->enum('protocol', ['http', 'https']);
            $table->enum('method', ['GET', 'POST', 'PUT', 'DELETE', 'PATCH']);
            $table->integer('concurrency_level');
            $table->json('request_headers')->nullable();
            $table->json('request_body')->nullable();
            $table->integer('total_requests');
            $table->integer('successful_requests');
            $table->integer('failed_requests');
            $table->float('average_response_time');
            $table->json('response_times')->nullable();
            $table->json('error_details')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('test_results');
    }
};
