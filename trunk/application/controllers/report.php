<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 2/26/13
 * Time: 6:06 PM
 * To change this template use File | Settings | File Templates.
 */

class Report_Controller extends Base_Controller
{

    private $reportRepo;
    private $schoolRepo;

    public function __construct()
    {
        parent::__construct();
        //add auth filter
        $this->filter('before', 'auth');
        $this->reportRepo = new ReportRepository();
        $this->schoolRepo = new SchoolRepository();

    }

    public function action_sms()
    {
        $classes = $this->schoolRepo->getClasses();
        $data['classes'] = $classes;
        return View::make('report/report',$data);
    }

    public function action_post_getSMS()
    {
        $data = Input::json();
        if (empty($data))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        $toDate = isset($data->toDate) ? new DateTime($data->toDate) : new DateTime();
        $fromDate = isset($data->fromDate) ? new DateTime($data->fromDate) : new DateTime();
        $classSections = isset($data->classSections) ? $data->classSections : array();
        $name = isset($data->name) ? $data->name : '';
        $pageCount = isset($data->pageCount) ? $data->pageCount : 25;
        $pageNumber = isset($data->pageNumber) ? $data->pageNumber : 1;
        $skip = $pageCount * ($pageNumber - 1);

        $filterSMS = $this->reportRepo->getSMS($classSections, $toDate, $fromDate, $name, $pageCount, $skip);
        if ($filterSMS == false && !is_array($filterSMS))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $result = array();
        foreach ($filterSMS as $smsRow) {
            $smsData = array();
            if ($smsRow->teacher_name != NULL)
                $smsData['name'] = $smsRow->teacher_name;
            else
                $smsData['name'] = $smsRow->student_name;
            $smsData['message'] = $smsRow->message;
            $smsData['status'] = $smsRow->status;
            $smsData['mobile']=$smsRow->mobile;
            $smsData['queue_time'] = $smsRow->queue_time;
            $smsData['sent_time'] = $smsRow->sent_time;
            $result[] = $smsData;

        }

        return Response::json($result);
    }
}