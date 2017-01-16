<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications',function (Blueprint $table){
            $table->increments('id');
            $table->boolean('sent');
            $table->string('title');
            $table->string('message');
            $table->string('image');
            $table->string('message_id');
            $table->text('payload');
            $table->unsignedInteger('count_delivered');
            $table->unsignedInteger('count_opened');
            $table->unsignedInteger('author')->references('id')->on('admins');
            $table->text('meta_audience');
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
        Schema::drop('notifications');
    }
}
