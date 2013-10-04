<?php

class Create_Tasks_Table
{

    /**
     * Make changes to the database.
     *
     * @return void
     */
    public function up()
    {
        //
        //create smsTemplate table
        Schema::create('tasks', function ($table) {
            $table->increments('id')->index()->unsigned();
            $table->string('name')->unique();
            $table->boolean('isRunning')->deafult(false);
            $table->timestamps();
        });

    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('tasks');
    }

}