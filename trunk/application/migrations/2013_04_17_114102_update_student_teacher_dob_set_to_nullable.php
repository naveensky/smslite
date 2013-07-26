<?php

class Update_Student_Teacher_Dob_Set_To_Nullable
{

    /**
     * Make changes to the database.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::query("ALTER TABLE  students CHANGE  dob  dob DATETIME NULL");
        DB::query("ALTER TABLE  teachers CHANGE  dob  dob DATETIME NULL");

    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down()
    {
        //
        DB::query("UPDATE students SET dob='now' WHERE dob is null");
        DB::query("UPDATE teachers SET dob='now' WHERE dob is null");
        DB::query("ALTER TABLE  students CHANGE  dob dob DATETIME NOT NULL");
        DB::query("ALTER TABLE  teachers CHANGE  dob  dob DATETIME NOT NULL");
    }

}