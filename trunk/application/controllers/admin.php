<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 21/3/13
 * Time: 5:18 PM
 * To change this template use File | Settings | File Templates.
 */

class Admin_Controller extends Base_Controller
{
    private $schoolRepo;
    private $adminRepo;
    private $smsRepo;

    public function __construct()
    {
        parent::__construct();
        //add auth filter
        $this->filter('before', 'auth');
        //proceed ahead if user is super admin
        $this->filter('before', Role::USER_ROLE_SUPER_ADMIN);
        $this->schoolRepo = new SchoolRepository();
        $this->adminRepo = new AdminRepository();
        $this->smsRepo = new SMSRepository();
    }


    public function action_allocate_credits()
    {
        return View::make('admin.allocatecredits');
    }

    public function action_post_allocate_credits()
    {
        $data = Input::json();
        if (empty($data) || count($data) == 0) {
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        }
        $schoolCode = isset($data->school) ? $data->school : null;
        $credits = isset($data->credits) ? $data->credits : null;
        $amount = isset($data->amount) ? $data->amount : null;
        $discount = isset($data->discount) && !empty($data->discount) ? $data->discount : 0;
        $orderId = isset($data->orderId) ? $data->orderId : null;
        $remarks = isset($data->remarks) ? $data->remarks : '';
        $sendToSchool = isset($data->sendToSchool) ? $data->sendToSchool : false;
        $ip = Request::ip();
        if (empty($schoolCode) || empty($credits) || empty($amount))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);

        $school = $this->schoolRepo->getSchool($schoolCode);
        if (empty($school))
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        $schoolId = $school[0]->id;
        $schoolMobile = $school[0]->contactMobile;
        $schoolName = $school[0]->name;
        $transaction = $this->adminRepo->createTransaction(Auth::user()->id, $orderId, $amount, $discount, $remarks, $credits, $schoolId, $ip);
        if (empty($transaction))
            return Response::json(array('status' => false, 'message' => 'Sorry error occured'));

        $adminEmailData = array();
        $adminEmailData['credits'] = $credits;
        $adminEmailData['amount'] = $amount;
        $adminEmailData['discount'] = $discount;
        $adminEmailData['schoolName'] = $schoolName;

        //sending email to admin of the system
        Event::fire(ListenerConstants::APP_ADMIN_CREDITS_ALLOTED, array($adminEmailData));


        if ($sendToSchool) {
            $users = School::find($schoolId)->users;
            $email = '';
            if (!empty($users)) {
                $emailData = array();
                $emailData['credits'] = $credits;
                $emailData['amount'] = $amount;
                $emailData['discount'] = $discount;
                $emailData['totalCredits'] = $this->smsRepo->getRemainingCredits($schoolId);
                $email = $users[0]->email;
                if (!empty($email)) {
                    $emailData['schoolEmail'] = $email;
                    Event::fire(ListenerConstants::APP_CREDITS_ALLOTED, array($emailData));
                }
                $credits_allocation_message = __('smstemplate.credits_alloted_message', array('credits' => $credits));
                $status = $this->smsRepo->createAppSms($schoolMobile, $credits_allocation_message, Config::get('sms.senderid'), Auth::user()->id);
            }
        }
        return Response::json(array('status' => true, 'message' => 'credits allocated successfully'));
    }

    public function action_sms_report()
    {
        return View::make('admin.smsreport');
    }

    public function action_post_sms_report()
    {
        $data = Input::json();
        if (empty($data) || count($data) == 0) {
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        }

        $toDate = isset($data->toDate) && !empty($data->toDate) ? new DateTime($data->toDate) : new DateTime();
        $fromDate = isset($data->fromDate) && !empty($data->fromDate) ? new DateTime($data->fromDate) : Util::getLast30DaysDate(new DateTime());
        $status = isset($data->status) ? $data->status : null;
        $pageCount = isset($data->pageCount) ? $data->pageCount : 25;
        $pageNumber = isset($data->pageNumber) ? $data->pageNumber : 1;
        $schoolCode = isset($data->selectedSchool) ? $data->selectedSchool : null;
        $skip = $pageCount * ($pageNumber - 1);

        try {
            $smsLog = $this->adminRepo->getSMSLog($toDate, $fromDate, $status, $schoolCode, $skip, $pageCount);
            return Response::json($smsLog);
        } catch (Exception $e) {
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        }

    }

    public function action_post_pie_chart_data()
    {
        $data = Input::json();
        $toDate = isset($data->toDate) && !empty($data->toDate) ? new DateTime($data->toDate) : new DateTime();
        $fromDate = isset($data->fromDate) && !empty($data->fromDate) ? new DateTime($data->fromDate) : Util::getLast30DaysDate(new DateTime());
        $status = isset($data->status) ? $data->status : null;
        $schoolCode = isset($data->selectedSchool) ? $data->selectedSchool : null;
        try {
            return Response::json($this->adminRepo->getPieChartData($toDate, $fromDate, $status, $schoolCode));
        } catch (Exception $e) {
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        }
    }

    public function action_schools_list()
    {
        return View::make('admin.schoollist');
    }

    public function action_post_schools_list()
    {
        $data = Input::json();
        if (empty($data) || count($data) == 0) {
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        }
        $name = isset($data->name) ? $data->name : null;
        $email = isset($data->email) ? $data->email : null;
        $registrationDate = isset($data->registrationDate) && !empty($data->registrationDate) ? new DateTime($data->registrationDate) : null;
        $pageCount = isset($data->pageCount) ? $data->pageCount : 20;
        $pageNumber = isset($data->pageNumber) ? $data->pageNumber : 1;
        $skip = $pageCount * ($pageNumber - 1);

        try {
            $schoolsData = $this->adminRepo->getListOfSchools($name, $email, $registrationDate, $skip, $pageCount);
            $schoolWiseData = array();
            $userIds = array();
            foreach ($schoolsData as $row) {
                $schoolWiseData[$row->id]['name'] = $row->name;
                $schoolWiseData[$row->id]['contactPerson'] = $row->contactPerson;
                $schoolWiseData[$row->id]['contactMobile'] = $row->contactMobile;
                $schoolWiseData[$row->id]['email'] = $row->email;
                $schoolWiseData[$row->id]['created_at'] = $row->created_at;
                $schoolWiseData[$row->id]['credits'] = $row->credits;
                $schoolWiseData[$row->id]['pendingSMS'] = 0;
                $schoolWiseData[$row->id]['sentSMS'] = 0;
                $userIds[] = $row->id;
            }
            if (!empty($userIds)) {
                $userPendingSMS = $this->smsRepo->getCountPendingSMSForUsers($userIds);
                $userSentSMS = $this->smsRepo->getCountSentSMSForUsers($userIds);
                foreach ($userPendingSMS as $dataRow) {
                    if (!empty($dataRow->userId))
                        $schoolWiseData[$dataRow->userId]['pendingSMS'] = $dataRow->count;
                }

                foreach ($userSentSMS as $sentData) {
                    if (!empty($sentData->userId))
                        $schoolWiseData[$sentData->userId]['sentSMS'] = $sentData->count;

                }
            }

            return Response::json(array_values($schoolWiseData));


        } catch (Exception $e) {
            return Response::make(__('responseerror.bad'), HTTPConstants::BAD_REQUEST_CODE);
        }
    }


}