<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersDescTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_desc',function (Blueprint $table)
        {
            $table -> string('rollno')->primary();

            $table->string('name');
            $table->unsignedTinyInteger('branch');
            $table->unsignedSmallInteger('batch');
            $table->string('email');
            $table->string('phone')->nullable();
            //This migration has some missing fields which are in the sql database table
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
        Schema::drop('users_desc');
    }
}
