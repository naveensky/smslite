<?php

class Add_New_Api_Columns_To_School_Table
{

    /**
     * Make changes to the database.
     *
     * @return void
     */
    public function up()
    {
        //adding api Key, student API URL, teacher API URL
        Schema::table('schools', function ($table) {
            $table->string('apiKey')->nullable();
            $table->string('studentAPIUrl', 1000)->nullable();
            $table->string('teacherAPIUrl')->nullable();
        });



    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schools', function ($table) {
            $table->drop_column('apiKey');
            $table->drop_column('studentAPIUrl');
            $table->drop_column('teacherAPIUrl');
        });

    }

}