<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 28/5/13
 * Time: 3:07 PM
 * To change this template use File | Settings | File Templates.
 */
class DataProviderService
{

    public function getStudentsData()
    {
        $data = array('key' => Config::get('app.rest_api_key'));
        $queryParam = http_build_query($data);
        $uri = Config::get('app.rest_api_url') . '/getStudents?' . $queryParam;
        $response = Httpful::get($uri)->send();
        return $response->body;
    }
}