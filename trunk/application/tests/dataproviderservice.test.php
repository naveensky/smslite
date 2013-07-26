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

    public function testGetSyncStudentData()
    {
        Bundle::start('httpful');
        $user = $this->getSampleUser();
        Auth::login($user->id);
        $user = $this->getSampleUser();
        $school = $user->school()->first();

        Auth::login($user->id);
        $parameters = array(
            'APIKey' => "asdf1234",
            'studentAPIUrl' => "http://demo.ccesoft.in/api/students/",
            'teacherAPIUrl' => "http://demo.ccesoft.in/api/teachers/",
            'fetchStudents' => true,
            'fetchTeachers' => false
        );

        Input::$json = (object)$parameters;
        $response = $this->post('sync@post_save', array());
        $result = json_decode($response);
        if (!$result->validationError) {
            if ($result->student->fetchStudent) {
                if (!$result->student->urlError) {
                    $totalFromAPI = $result->student->syncStatus->totalStudentsFromApi;
                    $importedStudents = $result->student->syncStatus->studentsImported;
                    $updatedStudents = $result->student->syncStatus->studentsUpdated;
                    $errors = $result->student->syncStatus->importKeys;
                    $this->assertEquals($totalFromAPI, $importedStudents + $updatedStudents + $errors);
                }
            }
        }

    }




}