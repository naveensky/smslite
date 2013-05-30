<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 28/5/13
 * Time: 3:48 PM
 * To change this template use File | Settings | File Templates.
 */
require_once 'controllertestcase.php';
class TestDataProviderService extends ControllerTestCase
{
    public function setUp()
    {
        $this->setupBeforeTests();
    }

    public function tearDown()
    {
        $this->tearDownAfterTests();
    }

    public function testGetSyncData()
    {
        Bundle::start('httpful');
        $dataProvider = new DataProviderService();
        $data = $dataProvider->getStudentsData();
        $studentRepo = new StudentRepository();
        $user = $this->getSampleUser();
        Auth::login($user->id);

        $firstStudent = FactoryMuff::create('Student');
        $firstStudent->schoolId = $user->school()->first()->id;
        $firstStudent->classStandard = "6";
        $firstStudent->classSection = "A";
        $firstStudent->save();
        $studentRepo->insertOrUpdate($data, $user->school()->first()->id);
        $student = Student::all();
        $this->assertEquals(3, count($student));

    }

    public function testUpdateSyncData()
    {
        Bundle::start('httpful');
        $dataProvider = new DataProviderService();
        $data = $dataProvider->getStudentsData();
        $studentRepo = new StudentRepository();
        $user = $this->getSampleUser();
        Auth::login($user->id);

        $firstStudent = FactoryMuff::create('Student');
        $firstStudent->schoolId = $user->school()->first()->id;
        $firstStudent->classStandard = "6";
        $firstStudent->classSection = "A";
        $firstStudent->uniqueIdentifier = 12456;
        $firstStudent->save();
        $studentRepo->insertOrUpdate($data, $user->school()->first()->id);
        $student = Student::all();
        $this->assertEquals(2, count($student));
    }

}