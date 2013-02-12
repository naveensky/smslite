<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 1/23/13
 * Time: 3:13 PM
 * To change this template use File | Settings | File Templates.
 */
class SMSRepository
{
    public $sms_transaction = array(); //array containing bulk sms of students
    public $smsTeachersTransaction = array(); //teachers containing bulk sms of students

    private $studentRepo;
    private $teacherRepo;

    public function __construct()
    {
        $this->studentRepo = new StudentRepository();
        $this->teacherRepo = new TeacherRepository();
    }

    /**
     * Returns the number of credit to be used for sending SMS
     * @param $message - message to be sent
     * @return int - no of credits to be used
     */
    public function countCredits($message)
    {
        if (strlen($message) <= ConstantCredit::SINGLE_MESSAGE_LIMIT) {
            return ConstantCredit::SINGLE_CREDIT;
        }

        return ConstantCredit::DOUBLE_CREDIT;
    }


    public function formatMessage($message)
    {
        $message = trim($message);
        $message = strlen($message) > ConstantCredit::MAXIMUM_MESSAGE_LENGTH ?
            substr($message, 0, ConstantCredit::MAXIMUM_MESSAGE_LENGTH) : $message;
        return $message;
    }

    /**
     * @param $message - Message to be sent
     * @param $studentCodes - array of codes of students to be messaged
     * @param $teachersCodes - array of codes of teachers to be messaged
     * @param $senderId - UNIQUE SENDER ID for School
     * @param $userId - auth userId for person sending message
     * @return array|bool
     */
    public function createSMS($message, $studentCodes, $teachersCodes, $senderId, $userId)
    {
        $message = $this->formatMessage($message);
        $credits = $this->countCredits($message);

        //find all students for given codes
        $students = $this->studentRepo->getStudentsFromCode($studentCodes);

        $insertedTransactions = array();
        //array containing data for sms to be sent

        foreach ($students as $student) {

            $mobiles = $this->getStudentMobileNumbers($student); //get all mobiles numbers for each student

            //if no mobile is present, skip inserting the record
            if (count($mobiles) == 0)
                continue;

            $sms_data['message'] = $message;
            $sms_data['credits'] = $credits;
            $sms_data['teacherId'] = NULL;
            $sms_data['userId'] = $userId;
            $sms_data['senderId'] = $senderId;
            $sms_data['status'] = SMSTransaction::SMS_STATUS_PENDING;
            $sms_data['studentId'] = $student->id;

            //a student might have multiple mobile. Create transaction for each of the mobile.
            foreach ($mobiles as $mobileNumber) {
                $sms_data['mobile'] = $mobileNumber;
                $this->sms_transaction[] = $sms_data;
            }
        }

        //we are splitting insert for student and teacher to segregate their status
        if (!empty($this->sms_transaction) && count($this->sms_transaction) > 0) {
            try {
                //using database transaction
                DB::connection()->pdo->beginTransaction();
                $statusStudents = SMSTransaction::insert($this->sms_transaction);
                DB::connection()->pdo->commit();
                $insertedTransactions['studentsStatus'] = $statusStudents;
            } catch (PDOException $e) {
                //rollback if any error while bulk insertion
                DB::connection()->pdo->rollBack();
                throw new PDOException("Exception while bulk insertion");
            }
            catch (Exception $e) {
                Log::exception($e);
                $insertedTransactions['studentsStatus'] = false;
            }
        }

        $teachers = $this->teacherRepo->getTeachersFromCode($teachersCodes);

        foreach ($teachers as $teacher) {

            $mobiles = $this->getTeacherMobileNumbers($teacher); //get all mobiles numbers for each student

            //if no mobile is present, skip inserting the record
            if (count($mobiles) == 0)
                continue;

            $sms_data['message'] = $message;
            $sms_data['credits'] = $credits;
            $sms_data['studentId'] = NULL;
            $sms_data['userId'] = $userId;
            $sms_data['senderId'] = $senderId;
            $sms_data['status'] = SMSTransaction::SMS_STATUS_PENDING;
            $sms_data['teacherId'] = $teacher->id;

            foreach ($mobiles as $mobileNumber) {
                $sms_data['mobile'] = $mobileNumber;
                $this->smsTeachersTransaction[] = $sms_data;
            }
        }


        //we are splitting insert for student and teacher to segregate their status
        if (!empty($this->smsTeachersTransaction) && count($this->smsTeachersTransaction) > 0)
            try {
                //database transaction support is used here
                DB::connection()->pdo->beginTransaction();
                $statusTeachers = SMSTransaction::insert($this->smsTeachersTransaction);
                DB::connection()->pdo->commit();
                $insertedTransactions['teachersStatus'] = $statusTeachers;
            } catch (PDOException $e) {
                //rollback the insertion if fail at any point.
                DB::connection()->pdo->rollBack();
                throw new PDOException("Exception while bulk insertion");
            }
            catch (Exception $e) {
                Log::exception($e);
                $insertedTransactions['teachersStatus'] = false;
            }

        if ($insertedTransactions['studentsStatus'] == false && $insertedTransactions['teachersStatus'] == false)
            return false;

        return $insertedTransactions;

    }

    public function getStudentMobileNumbers($student)
    {
        $mobiles = array();
        if ($student->mobile1 != NULL) {
            $mobiles[] = $student->mobile1;
        }

        if ($student->mobile2 != NULL) {
            $mobiles[] = $student->mobile2;
        }
        if ($student->mobile3 != NULL) {
            $mobiles[] = $student->mobile3;
        }
        if ($student->mobile4 != NULL) {
            $mobiles[] = $student->mobile4;
        }
        if ($student->mobile5 != NULL) {
            $mobiles[] = $student->mobile5;
        }

        return $mobiles;
    }


    public function getTeacherMobileNumbers($teacher)
    {
        $mobiles = array();
        if ($teacher->mobile1 != NULL) {
            $mobiles[] = $teacher->mobile1;
        }

        if ($teacher->mobile2 != NULL) {
            $mobiles[] = $teacher->mobile2;
        }
        if ($teacher->mobile3 != NULL) {
            $mobiles[] = $teacher->mobile3;
        }
        if ($teacher->mobile4 != NULL) {
            $mobiles[] = $teacher->mobile4;
        }
        if ($teacher->mobile5 != NULL) {
            $mobiles[] = $teacher->mobile5;
        }

        return $mobiles;
    }

    public function getAllPendingSMS()
    {
        $pendingSmsData = SMSTransaction::where_status("pending")->get();
        if (!empty($pendingSmsData))
            return $pendingSmsData;

        else
            return false;
    }

    public function updateStatus($id, $status)
    {

        $data = array(
            'status' => $status
        );
        try {
            $sms = SMSTransaction::update($id, $data);
        } catch (Exception $e) {
            Log::exception($e);
            return false;
        }

        return true;
    }

}
