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
            $studentName=Str::lower($studentName);
            $query = $query->where(("students.name"), '~*', ".*$studentName.*");//todo:check for match alternative use like
        }

        if (!empty($teacherName) && empty($studentName)) {
            $teacherName=Str::lower($teacherName);
            $query = $query->where('teachers.name', '~*', ".*$teacherName.*");//todo:check for match alternative use like
        }

        if (!empty($teacherName) && !empty($studentName)) {
            $query = $query->where(function ($query) use ($teacherName, $studentName) {
                $query->where("students.name", '~*', ".*$studentName.*");//todo:check for match alternative use like
                $query->or_where("teachers.name", '~*', ".*$teacherName.*");//todo:check for match alternative use like
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
            $smsLog = $query->skip($skip)->take($perPage)->get(array('students.name as student_name', 'teachers.name as teacher_name', 'smsTransactions.mobile', 'smsTransactions.message', 'smsTransactions.status', 'smsTransactions.created_at as queue_time', 'smsTransactions.updated_at as sent_time'));
        } catch (Exception $e) {
            Log::exception($e);
            return false;
        }
        return $smsLog;

    }

}