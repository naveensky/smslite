<?php

class Alter_Student_And_Teacher_Add_Import_Key
{

    /**
     * Make changes to the database.
     *
     * @return void
     */
    public function up()
    {
        //adding importKeyColumn to student table
        Schema::table('students', function ($table) {
            $table->integer('importKey')->nullable();
        });

        //adding importKeyColumn to student table
        Schema::table('teachers', function ($table) {
            $table->integer('importKey')->nullable();
        });
    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function ($table) {
            $table->drop_column('importKey');
        });

        Schema::table('teachers', function ($table) {
            $table->drop_column('importKey');
        });

    }

}