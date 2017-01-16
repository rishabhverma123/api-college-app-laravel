<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsFeedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_feeds',function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('author')->references('id')->on('users');
            $table->string('image'); //optional url
            $table->text('content');
            $table->string('url');  //to be opened on click
            $table->unsignedSmallInteger('commentCount')->default(0);  //
            $table->dateTime('timestamp');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('news_feed');
    }
}
