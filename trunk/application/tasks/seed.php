<?php
/**
 * Created by JetBrains PhpStorm.
 * User: naveen
 * Date: 12/2/13
 * Time: 7:17 PM
 * To change this template use File | Settings | File Templates.
 */

/**
 * Class Seed_Task - Task class for creating Seed Data
 */
class Seed_Task
{
    public function run($arguments)
    {
        //create sample school
        $school = new School();
        $school->name = "Sample School";
        $school->code = Str::random(64, 'alnum');
        $school->address = "Random Address";
        $school->city = "City";
        $school->zip = "Zip";
        $school->state = "Zip";
        $school->senderId = "SCHOOL";
        $school->contactPerson = "";
        $school->contactMobile = "";
        $school->save();

        //create sample user
        $user = new User();
        $user->email = "naveen@greenapplesolutions.com";
        $user->password = Hash::make('asdf');
        $user->isVerified = 1;
        $user->mobile = "9891410701";

        $school->users()->insert($user);
    }
}