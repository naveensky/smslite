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
        Bundle::start('factorymuff');

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

        $user2 = new User();
        $user2->email = "hmalhotra@greenapplesolutions.com";
        $user2->password = Hash::make('asdf');
        $user2->isVerified = 1;
        $user2->mobile = "9891410701";

        $school->users()->insert($user2);

        $smsCredit = new SMSCredit();
        $smsCredit->schoolId = $school->id;
        $smsCredit->credits = 100;
        $smsCredit->save();

        $transaction1 = FactoryMuff::create('Transaction');
        $transaction1->schoolId = $school->id;
        $transaction1->orderId = Str::random(64, 'alnum');
        $transaction1->save();

        $transaction2 = FactoryMuff::create('Transaction');
        $transaction2->schoolId = $school->id;
        $transaction2->orderId = Str::random(64, 'alnum');
        $transaction2->save();

        $student = FactoryMuff::create('Student');
        $student->schoolId = $school->id;
        $student->save();


        $teacher = FactoryMuff::create('Teacher');
        $teacher->schoolId = $school->id;
        $teacher->save();
    }
}