<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->unsigned()->nullable();
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->string('text')->nullable();
            $table->string('link')->nullable();
            $table->string('image')->nullable();
            $table->string('pdf')->nullable();
            $table->string('video')->nullable();
            $table->string('audio')->nullable();
            $table->boolean('group')->nullable();
            $table->boolean('individual')->nullable();
            $table->date('send_date')->nullable();
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
        Schema::dropIfExists('message_logs');
    }
}
