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
    private $appSmsRepo;

    public function __construct()
    {
        parent::__construct();

        //add auth filter
        $this->filter('before', 'auth');
        //proceed ahead if user is super admin
        $this->filter('before', 'superadmin');
        $this->schoolRepo = new SchoolRepository();
        $this->adminRepo = new AdminRepository();
        $this->appSmsRepo = new AppSMSRepository();

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
                $email = $users[0]->email;
                if (!empty($email)) {
                    $emailData['schoolEmail'] = $email;
                    Event::fire(ListenerConstants::APP_CREDITS_ALLOTED, array($emailData));
                }
                $credits_allocation_message = __('smstemplate.credits_alloted_message', array('credits' => $credits));
                $status = $this->appSmsRepo->createAppSms($schoolMobile, $credits_allocation_message, Config::get('sms.senderid'), Auth::user()->id);
            }
        }
        return Response::json(array('status' => true, 'message' => 'credits allocated successfully'));
    }


}