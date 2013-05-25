<?php

class Create_School_Request_For_Template
{

    /**
     * Make changes to the database.
     *
     * @return void
     */
    public function up()
    {
        //create smsTemplate table
        Schema::create('requestedTemplates', function ($table) {
            $table->increments('id')->unsigned();
            $table->string('body', 1000);
            $table->string('name', 100);
            $table->string('status')->default('pending');
            $table->integer('schoolId')->unsigned();
            $table->foreign('schoolId')->references('id')->on('schools');
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
        Schema::table('requestedTemplate', function ($table) {
            $table->drop_foreign('requestedTemplate_schoolId_foreign');
        });
        Schema::drop('requestedTemplate');
    }

}