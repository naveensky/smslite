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
    private $studentRepo;
    private $teacherRepo;

    public function  __construct()
    {
        $this->studentRepo = new StudentRepository();
        $this->teacherRepo = new TeacherRepository();
    }

    public function getStudentsData($apiKey, $studentAPIUrl, $schoolId)
    {
        $studentSyncStatus = array();
        $pageNumber = 1;
        $pageCount = 1000;
        $isURLInvalid = false;
        $totalStudentsFromAPI = 0;
        for ($i = 1; $i <= PHP_INT_MAX; $i += $pageCount) {
            $data = array('key' => $apiKey, 'page' => $pageNumber, 'pageCount' => $pageCount);
            $queryParam = http_build_query($data);
            $uri = $studentAPIUrl . '?' . $queryParam;
            $response = Httpful::get($uri)->send();
            if ($response->code == 404) {
                $isURLInvalid = true;
                break;
            }
            if ($response->code == 403) {
                throw new InvalidArgumentException("Invalid API Key");
            }

            if (empty($response->body))
                break;
            try {
                $totalStudentsFromAPI += count($response->body);
                $studentSyncStatus[] = $this->studentRepo->insertOrUpdate($response->body, $schoolId);
            } catch (Exception $e) {
                Log::exception($e);
            }
            $pageNumber++;
        }
        if ($isURLInvalid)
            return array('status' => false);
        $totalStudentsImported = 0;
        $totalStudentsUpdated = 0;
        $importKeysErrors = 0;
        foreach ($studentSyncStatus as $row) {
            if ($row['status'] == true) {
                $totalStudentsImported += $row['studentsImported'];
                $totalStudentsUpdated += $row['studentsUpdated'];
                $importKeysErrors += count($row['importErrors']);
            }
        }
        return array('status' => true, 'studentsImported' => $totalStudentsImported, 'studentsUpdated' => $totalStudentsUpdated, 'importKeys' => $importKeysErrors, 'totalStudentsFromApi' => $totalStudentsFromAPI);
    }

    public function getTeachersData($apiKey, $teacherAPIUrl, $schoolId)
    {
        $teacherSyncStatus = array();
        $pageNumber = 1;
        $pageCount = 1000;
        $isURLInvalid = false;
        $totalTeachersFromAPI = 0;
        for ($i = 1; $i <= PHP_INT_MAX; $i += $pageCount) {
            $data = array('key' => $apiKey, 'page' => $pageNumber, 'pageCount' => $pageCount);
            $queryParam = http_build_query($data);
            $uri = $teacherAPIUrl . '?' . $queryParam;
            $response = Httpful::get($uri)->send();
            if ($response->code == 404) {
                $isURLInvalid = true;
                break;
            }
            if ($response->code == 403) {
                throw new InvalidArgumentException("Invalid API Key");
            }
            if (empty($response->body))
                break;
            try {
                $totalTeachersFromAPI += count($response->body);
                $teacherSyncStatus[] = $this->teacherRepo->insertOrUpdate($response->body, $schoolId);
            } catch (Exception $e) {
                Log::exception($e);
            }
            $pageNumber++;
        }
        if ($isURLInvalid)
            return array('status' => false);
        $totalTeachersImported = 0;
        $totalTeachersUpdated = 0;
        $importKeysErrors = 0;
        foreach ($teacherSyncStatus as $row) {
            if ($row['status'] == true) {
                $totalTeachersImported += $row['teachersImported'];
                $totalTeachersUpdated += $row['teachersUpdated'];
                $importKeysErrors += count($row['importErrors']);
            }
        }
        return array('status' => true, 'teachersImported' => $totalTeachersImported, 'teachersUpdated' => $totalTeachersUpdated, 'importKeys' => $importKeysErrors, 'totalTeachersFromApi' => $totalTeachersFromAPI);
    }
}