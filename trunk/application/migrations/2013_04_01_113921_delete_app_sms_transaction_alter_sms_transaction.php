<?php

class Delete_App_Sms_Transaction_Alter_Sms_Transaction
{

    /**
     * Make changes to the database.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('appSmsTransactions', function ($table) {
            $table->drop_foreign('appsmstransactions_userId_foreign');
        });

        Schema::drop('appSmsTransactions');

        //adding new name and priority SMS Transaction Table
        Schema::table('smsTransactions', function ($table) {
            $table->string('name')->nullable();
            $table->integer('priority')->index()->default(1);
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
        //create Application SMS Transaction Table
        Schema::create('appSmsTransactions', function ($table) {
            $table->increments('id')->unsigned();
            $table->string('mobile', 45);
            $table->text('message');
            $table->string('status', 45);
            $table->integer('credits');
            $table->string('senderId');
            $table->integer('userId')->unsigned();
            $table->foreign('userId')->references('id')->on('users');
            $table->timestamps();
        });

        Schema::table('smsTransactions', function ($table) {
            $table->drop_column('name');
            $table->drop_column('priority');
        });
    }

}