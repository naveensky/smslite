<?php

class Create_Smstemplate_Table
{

    /**
     * Make changes to the database.
     *
     * @return void
     */
    public function up()
    {
        //create smsTemplate table
        Schema::create('smsTemplate', function ($table) {
            $table->increments('id')->unsigned();
            $table->string('body',1000);
            $table->integer('schoolId')->unsigned();
            $table->foreign('schoolId')->references('id')->on('schools');
            $table->integer('useCount');
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
        Schema::table('smsTemplate', function ($table) {
            $table->drop_foreign('smsTemplate_schoolId_foreign');
        });

        Schema::drop('smsTemplate');

    }

}