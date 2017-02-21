<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionPapersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_papers',function (Blueprint $table){
            $table->increments('id');
            $table->string('subject');
            $table->boolean('type'); //CT(0) or Semester Paper(1)
            $table->string('filename');
            $table->string('contributor');
            $table->unsignedTinyInteger('semester');
            $table->unsignedTinyInteger('branch');
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('question_papers');
    }
}
