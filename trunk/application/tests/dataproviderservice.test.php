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

    public function testGetData()
    {
        Bundle::start('httpful');
        $dataProvider = new DataProviderService();
        $data = $dataProvider->getStudentsData();
        var_dump($data);
    }

}