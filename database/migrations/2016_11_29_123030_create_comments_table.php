<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments',function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('author')->references('id')->on('users');
            $table->unsignedInteger('feedId')->references('id')->on('news_feeds');
            $table->string('image'); //optional url
            $table->text('content');
            $table->string('url');  //to be opened on click
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
        Schema::drop('comments');
    }
}
