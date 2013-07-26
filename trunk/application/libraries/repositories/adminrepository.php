<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 22/3/13
 * Time: 4:38 PM
 * To change this template use File | Settings | File Templates.
 */

class AdminRepository
{
    private $smsRepo;

    public function __construct()
    {
        $this->smsRepo = new SMSRepository();
    }

    public function createTransaction($userId, $orderId, $amount, $discount, $remarks, $smsCredits, $schoolId, $ip)
    {
        $grossAmount = $amount - ($discount / 100 * $amount);
        $transaction = new Transaction();
        $transaction->orderId = $orderId;
        $transaction->amount = $amount;
        $transaction->discount = $discount;
        $transaction->grossAmount = $grossAmount;
        $transaction->remarks = $remarks;
        $transaction->smsCredits = $smsCredits;
        $transaction->schoolId = $schoolId;
        $transaction->userId = $userId;
        $transaction->ip = $ip;
        try {
            DB::connection()->pdo->beginTransaction();
            $transaction->save();
            $this->smsRepo->addCredits($smsCredits, $schoolId);
            DB::connection()->pdo->commit();
        } catch (PDOException $e) {
            //rollback if any error while bulk insertion
            DB::connection()->pdo->rollBack();
            Log::exception($e);
            return false;
        }
        catch (Exception $e) {
            Log::exception($e);
            return false;
        }
        return $transaction;
    }

    public function getSMSLog(DateTime $toDate, DateTime $fromDate, $status, $schoolCode, $skip, $take)
    {
        try {
            $query = DB::table('smsTransactions')
                ->join('users', 'smsTransactions.userId', '=', 'users.id')
                ->join('schools', 'users.schoolId', '=', 'schools.id');
            $fromDate = date('Y', $fromDate->getTimestamp()) . "-" . date('m', $fromDate->getTimestamp()) . "-" . date('d', $fromDate->getTimestamp()) . " 00:00:00";
            $fromDate = new DateTime($fromDate);
            $toDate = date('Y', $toDate->getTimestamp()) . "-" . date('m', $toDate->getTimestamp()) . "-" . date('d', $toDate->getTimestamp()) . " 00:00:00";
            $toDate = new DateTime($toDate);
            $toDate->add(new DateInterval('P1D'));
            if (!empty($schoolCode))
                $query = $query->where("schools.code", '=', $schoolCode);

            $query = $query->where(function ($query) use ($fromDate, $toDate) {
                $query->where("smsTransactions.created_at", '>=', $fromDate);
                $query->where("smsTransactions.updated_at", '<', $toDate);
            });

            if (!empty($status)) {
                $query = $query->where("smsTransactions.status", '=', $status);
            }



            $smsLog = $query->skip($skip)->take($take)->order_by(DB::raw('smsTransactions.created_at'), 'Desc')->get(array('schools.name as school', 'smsTransactions.name as name', 'smsTransactions.mobile as mobile', 'smsTransactions.message as message', 'smsTransactions.created_at as queueTime', 'smsTransactions.updated_at as sentTime', 'smsTransactions.status as status'));
            return $smsLog;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getPieChartData(DateTime $toDate, DateTime $fromDate, $status, $schoolCode)
    {
        try {
            $fromDate = date('Y', $fromDate->getTimestamp()) . "-" . date('m', $fromDate->getTimestamp()) . "-" . date('d', $fromDate->getTimestamp()) . " 00:00:00";
            $fromDate = new DateTime($fromDate);
            $toDate = date('Y', $toDate->getTimestamp()) . "-" . date('m', $toDate->getTimestamp()) . "-" . date('d', $toDate->getTimestamp()) . " 00:00:00";
            $toDate = new DateTime($toDate);
            $toDate->add(new DateInterval('P1D'));
            if (!empty($status)) {
                $sentSMS = 0;
                $pendingSMS = 0;
                $failedSMS = 0;
                if ($status == 'pending') {
                    $pendingQuery = DB::table('smsTransactions')->join('users', 'smsTransactions.userId', '=', 'users.id')
                        ->join('schools', 'users.schoolId', '=', 'schools.id')->where("smsTransactions.created_at", '>=', $fromDate)->where("smsTransactions.updated_at", '<', $toDate)->where('status', '=', 'pending');
                    if (!empty($schoolCode))
                        $pendingQuery = $pendingQuery->where('schools.code', '=', $schoolCode);
                    $pendingSMS = $pendingQuery->count();
                }
                if ($status == 'fail') {
                    $failQuery = DB::table('smsTransactions')->join('users', 'smsTransactions.userId', '=', 'users.id')
                        ->join('schools', 'users.schoolId', '=', 'schools.id')->where("smsTransactions.created_at", '>=', $fromDate)->where("smsTransactions.updated_at", '<', $toDate)->where('status', '=', 'fail');
                    if (!empty($schoolCode))
                        $failQuery = $failQuery->where('schools.code', '=', $schoolCode);
                    $failedSMS = $failQuery->count();
                }
                if ($status == 'sent') {
                    $sentQuery = DB::table('smsTransactions')->join('users', 'smsTransactions.userId', '=', 'users.id')
                        ->join('schools', 'users.schoolId', '=', 'schools.id')->where("smsTransactions.created_at", '>=', $fromDate)->where("smsTransactions.updated_at", '<', $toDate)->where('status', '=', 'sent');
                    if (!empty($schoolCode))
                        $sentQuery = $sentQuery->where('schools.code', '=', $schoolCode);
                    $sentSMS = $sentQuery->count();
                }
            } else {

                $pendingQuery = DB::table('smsTransactions')->join('users', 'smsTransactions.userId', '=', 'users.id')
                    ->join('schools', 'users.schoolId', '=', 'schools.id')->where("smsTransactions.created_at", '>=', $fromDate)->where("smsTransactions.updated_at", '<', $toDate)->where('status', '=', 'pending');
                if (!empty($schoolCode))
                    $pendingQuery = $pendingQuery->where('schools.code', '=', $schoolCode);
                $pendingSMS = $pendingQuery->count();


                $failQuery = DB::table('smsTransactions')->join('users', 'smsTransactions.userId', '=', 'users.id')
                    ->join('schools', 'users.schoolId', '=', 'schools.id')->where("smsTransactions.created_at", '>=', $fromDate)->where("smsTransactions.updated_at", '<', $toDate)->where('status', '=', 'fail');
                if (!empty($schoolCode))
                    $failQuery = $failQuery->where('schools.code', '=', $schoolCode);
                $failedSMS = $failQuery->count();


                $sentQuery = DB::table('smsTransactions')->join('users', 'smsTransactions.userId', '=', 'users.id')
                    ->join('schools', 'users.schoolId', '=', 'schools.id')->where("smsTransactions.created_at", '>=', $fromDate)->where("smsTransactions.updated_at", '<', $toDate)->where('status', '=', 'sent');
                if (!empty($schoolCode))
                    $sentQuery = $sentQuery->where('schools.code', '=', $schoolCode);
                $sentSMS = $sentQuery->count();

            }
            return array(array('name' => 'Queued', 'color' => 'Grey', 'y' => intval($pendingSMS)), array('name' => 'Sent', 'color' => 'Green', 'y' => intval($sentSMS)), array('name' => 'Failed', 'color' => 'Red', 'y' => intval($failedSMS)));
        } catch (Exception $e) {
            Log::error($e);
            throw $e;
        }
    }

    public function getListOfSchools($name, $email, $registrationDate, $skip, $take)
    {
        try {
            $query = DB::table('users')
                ->join('schools', 'users.schoolId', '=', 'schools.id')->join('smsCredits', 'smsCredits.schoolId', '=', 'schools.id');
            if (!empty($name)) {
                $query = $query->where(function ($query) use ($name) {
                    $query->where("schools.name", 'like', "%$name%");
                    $query->or_where("schools.contactPerson", 'like', "%$name%");
                });
            }

            if (!empty($email)) {
                $query->where("users.email", 'like', "%$email%");
            }


            if (!empty($registrationDate)) {
                $registrationDate = date('Y', $registrationDate->getTimestamp()) . "-" . date('m', $registrationDate->getTimestamp()) . "-" . date('d', $registrationDate->getTimestamp()) . " 00:00:00";
                $registrationDate = new DateTime($registrationDate);
                $registrationDate->add(new DateInterval('P1D'));
                $query->where('schools.created_at', '>', $registrationDate);
            }

            return $query->skip($skip)->take($take)->get(array('smsCredits.credits', 'users.id', 'schools.name', 'schools.contactPerson', 'schools.contactMobile', 'users.email', 'schools.created_at'));
        } catch (Exception $e) {
            Log::error($e);
            throw $e;
        }
    }


}