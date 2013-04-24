<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 2/26/13
 * Time: 5:56 PM
 * To change this template use File | Settings | File Templates.
 */

class ReportRepository
{
    private $studentRepo;

    public function __construct()
    {
        $this->studentRepo = new StudentRepository();
    }

    public function getSMS($schoolId, $classSections, DateTime $toDate, DateTime $fromDate, $studentName, $teacherName, $perPage, $skip)
    {
        $query = DB::table('smsTransactions')
            ->left_join('students', 'smsTransactions.studentId', '=', 'students.id')
            ->left_join('teachers', 'smsTransactions.teacherId', '=', 'teachers.id')
            ->where(function ($query) use ($schoolId) {
                $query->where("students.schoolId", '=', $schoolId);
                $query->or_where("teachers.schoolId", '=', $schoolId);
            });

        if (!empty($classSections)) {
            $that = $this->studentRepo; //to use context to anonymous function (passing $that to function)
            $query = $query->where(function ($query) use ($classSections, $that) {
                $count = 1;
                foreach ($classSections as $classSection) {

                    $class = $that->getClass($classSection); //getting class from classSection
                    $section = $that->getSection($classSection); //getting section from classSection

                    if ($count == 1) {
                        $query = $query->where(function ($query) use ($class, $section) {
                            $query->where("classStandard", '=', $class);
                            $query->where("classSection", '=', $section);
                        });
                    } else {
                        $query = $query->or_where(function ($query) use ($class, $section) {
                            $query->where("classStandard", '=', $class);
                            $query->where("classSection", '=', $section);
                        });
                    }

                    ++$count;
                }
            });
        }

        if (!empty($studentName) && empty($teacherName)) {
            $studentName = Str::lower($studentName);
            $query = $query->where(("smsTransactions.name"), '~*', ".*$studentName.*"); //todo:check for match alternative use like
        }

        if (!empty($teacherName) && empty($studentName)) {
            $teacherName = Str::lower($teacherName);
            $query = $query->where('smsTransactions.name', '~*', ".*$teacherName.*"); //todo:check for match alternative use like
        }

        if (!empty($teacherName) && !empty($studentName)) {
            $query = $query->where(function ($query) use ($teacherName, $studentName) {
                $query->where("smsTransactions.name", '~*', ".*$studentName.*"); //todo:check for match alternative use like
                $query->or_where("smsTransactions.name", '~*', ".*$teacherName.*"); //todo:check for match alternative use like
            });
        }


        if ($fromDate != new DateTime()) {
            $fromDate = date('Y', $fromDate->getTimestamp()) . "-" . date('m', $fromDate->getTimestamp()) . "-" . date('d', $fromDate->getTimestamp()) . " 00:00:00";
            $fromDate = new DateTime($fromDate);
            $toDate = date('Y', $toDate->getTimestamp()) . "-" . date('m', $toDate->getTimestamp()) . "-" . date('d', $toDate->getTimestamp()) . " 00:00:00";
            $toDate = new DateTime($toDate);
            $toDate->add(new DateInterval('P1D'));
            $query = $query->where(function ($query) use ($fromDate, $toDate) {
                $query->where("smsTransactions.created_at", '>=', $fromDate);
                $query->where("smsTransactions.updated_at", '<', $toDate);
            });
        }

        try {
            $smsLog = $query->skip($skip)->take($perPage)->order_by(DB::raw('"smsTransactions"."created_at"'), 'Desc')->get(array('smsTransactions.name', 'smsTransactions.mobile', 'smsTransactions.message', 'smsTransactions.status', 'smsTransactions.created_at as queue_time', 'smsTransactions.updated_at as sent_time'));
        } catch (Exception $e) {
            Log::exception($e);
            return false;
        }
        return $smsLog;
    }

    //get last 30 days sms delivered
    public function getLast30DaysSMSSentStatus($schoolId)
    {
        $dateWiseData = array();
        $toDate = new DateTime();
        for ($i = 0; $i < 30; $i++) {
            $dateWiseData[$toDate->format('Y-m-d')] = 0;
            $toDate = Util::getFromDate($toDate);
        }
        $fromDate = Util::getToDate(new DateTime())->format("Y-m-d");
        $count = DB::query('select "smsTransactions"."updated_at"::date,count("smsTransactions"."id") as countSMS from "smsTransactions" join "users" on "smsTransactions"."userId"="users"."id" where "schoolId" =' . $schoolId . ' and "status"=\'' . SMSTransaction::SMS_STATUS_SENT . '\'and "smsTransactions"."updated_at" >= ' . '\'' . $toDate->format("Y-m-d") . '\' and "smsTransactions"."updated_at" < \'' . $fromDate . ' \' group by "smsTransactions"."updated_at"::date order by "smsTransactions"."updated_at"::date DESC');
        foreach ($count as $row) {
            $dateWiseData[$row->updated_at] = $row->countsms;
        }

        return array_reverse($dateWiseData, true);

    }

    public function getLast30DaysSMSFailStatus($schoolId)
    {
        $dateWiseData = array();
        $toDate = new DateTime();
        for ($i = 0; $i < 30; $i++) {
            $dateWiseData[$toDate->format('Y-m-d')] = 0;
            $toDate = Util::getFromDate($toDate);
        }
        $fromDate = Util::getToDate(new DateTime())->format("Y-m-d");
        $count = DB::query('select "smsTransactions"."updated_at"::date,count("smsTransactions"."id") as countSMS from "smsTransactions" join "users" on "smsTransactions"."userId"="users"."id" where "schoolId" =' . $schoolId . ' and "status"=\'' . SMSTransaction::SMS_STATUS_FAIL . '\'and "smsTransactions"."updated_at" >= ' . '\'' . $toDate->format("Y-m-d") . '\' and "smsTransactions"."updated_at" < \'' . $fromDate . ' \' group by "smsTransactions"."updated_at"::date order by "smsTransactions"."updated_at"::date DESC');
        foreach ($count as $row) {
            $dateWiseData[$row->updated_at] = $row->countsms;
        }

        return array_reverse($dateWiseData, true);

    }


    //get last 30 days sms Queued
    public function getLast30DaysSMSQueuedStatus($schoolId)
    {
        $dateWiseData = array();
        $toDate = new DateTime();
        for ($i = 0; $i < 30; $i++) {
            $dateWiseData[$toDate->format('Y-m-d')] = 0;
            $toDate = Util::getFromDate($toDate);
        }
        $fromDate = Util::getToDate(new DateTime())->format("Y-m-d");
        $count = DB::query('select "smsTransactions"."created_at"::date,count("smsTransactions"."id") as countSMS from "smsTransactions" join "users" on "smsTransactions"."userId"="users"."id" where "schoolId" =' . $schoolId . 'and "smsTransactions"."created_at" >= ' . '\'' . $toDate->format("Y-m-d") . '\' and "smsTransactions"."created_at" < \'' . $fromDate . ' \' group by "smsTransactions"."created_at"::date order by "smsTransactions"."created_at"::date DESC');
        foreach ($count as $row) {
            $dateWiseData[$row->created_at] = $row->countsms;
        }
        return array_reverse($dateWiseData, true);
    }

    public function getLast30DaysSMS($schoolId)
    {
        $sentSMSData = $this->getLast30DaysSMSSentStatus($schoolId);
        $queueSMSData = $this->getLast30DaysSMSQueuedStatus($schoolId);
        $failSMSData = $this->getLast30DaysSMSFailStatus($schoolId);
        $dates = array();
        foreach ($sentSMSData as $key => $value) {
            $key = explode('-', $key);
            $dates[] = ltrim($key[2], 0);
        }
        $sentValues = array_values($sentSMSData);
        $queueValues = array_values($queueSMSData);
        $failValues = array_values($failSMSData);

        return array('dates' => $dates, 'sentValues' => $sentValues, 'queueValues' => $queueValues, 'failValues' => $failValues);
    }

}