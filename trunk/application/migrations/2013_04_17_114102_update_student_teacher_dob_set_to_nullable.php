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
        DB::query("ALTER TABLE students ALTER COLUMN dob DROP NOT NULL");
        DB::query("ALTER TABLE teachers ALTER COLUMN dob DROP NOT NULL");

    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down()
    {
        //
        DB::query("ALTER TABLE students ALTER COLUMN dob SET NOT NULL");
        DB::query("ALTER TABLE teachers ALTER COLUMN dob SET NOT NULL");
    }

}