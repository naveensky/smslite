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

    public function getRemainingCredits($schoolId)
    {
        $schoolCredits = SMSCredit::where('schoolId', '=', $schoolId)->first();
        if (empty($schoolCredits))
            return 0;

        return $schoolCredits->credits;
    }

    public function updateCredits($totalCreditsUsed, $schoolId)
    {
        $SMSCredit = SMSCredit::where('schoolId', '=', $schoolId)->first();
        $creditsLeft = $SMSCredit->credits - $totalCreditsUsed;
        $attributes = array('credits' => $creditsLeft);
        $status = SMSCredit::update($SMSCredit->id, $attributes);
        return $status;
    }

    public function createSMS(array $studentCodes, array $teachersCodes, $senderId, $userId, $schoolId)
    {
        //get all student codes
        $codes = array_keys($studentCodes);

        //find all students for given codes
        $students = $this->studentRepo->getStudentsFromCodes($codes);

        //array containing data for sms to be sent
        $insertedTransactions = array();
        //count the credits used for the message sent to students
        $totalStudentCredits = 0;
        foreach ($students as $student) {

            $mobiles = $this->getStudentMobileNumbers($student); //get all mobiles numbers for each student
            //if no mobile is present, skip inserting the record
            if (count($mobiles) == 0)
                continue;

            $sms_data['message'] = $this->formatMessage($studentCodes[$student->code]);
            $creditsForMessage = $this->countCredits($sms_data['message']);
            $sms_data['credits'] = $creditsForMessage;
            $sms_data['teacherId'] = NULL;
            $sms_data['userId'] = $userId;
            $sms_data['senderId'] = $senderId;
            $sms_data['status'] = SMSTransaction::SMS_STATUS_PENDING;
            $sms_data['created_at'] = new DateTime(); //this a pain but laravel doesnt set these values in bulk operations.
            $sms_data['updated_at'] = new DateTime(); //this a pain but laravel doesnt set these values in bulk operations.
            $sms_data['studentId'] = $student->id;

            //a student might have multiple mobile. Create transaction for each of the mobile.
            foreach ($mobiles as $mobileNumber) {
                $sms_data['mobile'] = $mobileNumber;
                $this->sms_transaction[] = $sms_data;
                $totalStudentCredits += $creditsForMessage;
            }
        }

        $teacher_codes = array_keys($teachersCodes);
        $teachers = $this->teacherRepo->getTeachersFromCodes($teacher_codes);
        $teacherCreditsUsed = 0;
        foreach ($teachers as $teacher) {

            $mobiles = $this->getTeacherMobileNumbers($teacher); //get all mobiles numbers for each student

            //if no mobile is present, skip inserting the record
            if (count($mobiles) == 0)
                continue;

            $sms_data['message'] = $this->formatMessage($teachersCodes[$teacher->code]);
            $sms_data['credits'] = $this->countCredits($sms_data['message']);
            $sms_data['studentId'] = NULL;
            $sms_data['userId'] = $userId;
            $sms_data['senderId'] = $senderId;
            $sms_data['status'] = SMSTransaction::SMS_STATUS_PENDING;
            $sms_data['teacherId'] = $teacher->id;
            $sms_data['created_at'] = new DateTime(); //this a pain but laravel doesnt set these values in bulk operations.;
            $sms_data['updated_at'] = new DateTime(); //this a pain but laravel doesnt set these values in bulk operations.;
            foreach ($mobiles as $mobileNumber) {
                $sms_data['mobile'] = $mobileNumber;
                $this->smsTeachersTransaction[] = $sms_data;
                $teacherCreditsUsed += $sms_data['credits'];
            }
        }

        if (!$this->checkCreditsBalance(($totalStudentCredits + $teacherCreditsUsed), $schoolId))
            throw new InsufficientCreditsException("Insufficient credits in account of school $schoolId");

        //we are splitting insert for student and teacher to segregate their status
        try {
            //using database transaction
            DB::connection()->pdo->beginTransaction();
            if (!empty($this->sms_transaction) && count($this->sms_transaction) > 0) {
                $statusStudents = SMSTransaction::insert($this->sms_transaction);
                $this->updateCredits($totalStudentCredits, $schoolId);
            }

            if (!empty($this->smsTeachersTransaction) && count($this->smsTeachersTransaction) > 0) {
                $statusTeachers = SMSTransaction::insert($this->smsTeachersTransaction);
                $this->updateCredits($teacherCreditsUsed, $schoolId);
            }
            DB::connection()->pdo->commit();

            if (!empty($this->sms_transaction) && count($this->sms_transaction) > 0) {
                $insertedTransactions['studentsStatus'] = $statusStudents;
                $insertedTransactions['numberofstudentinserted'] = count($this->sms_transaction);
                $insertedTransactions['numberOfCreditsUsed'] = $totalStudentCredits;
            }
            if (!empty($this->smsTeachersTransaction) && count($this->smsTeachersTransaction) > 0) {
                $insertedTransactions['teachersStatus'] = $statusTeachers;
                $insertedTransactions['numberofteacherinserted'] = count($this->smsTeachersTransaction);
                $insertedTransactions['numberOfCreditsUsedTeachers'] = $teacherCreditsUsed;
            }
        } catch (PDOException $e) {
            //rollback if any error while bulk insertion
            DB::connection()->pdo->rollBack();
            throw new PDOException("Exception while bulk insertion");
        }
        catch (Exception $e) {
            DB::connection()->pdo->rollBack();
            Log::exception($e);
            $insertedTransactions['studentsStatus'] = false;
            $insertedTransactions['numberofstudentinserted'] = 0;
            $insertedTransactions['numberOfCreditsUsed'] = 0;
            $insertedTransactions['teachersStatus'] = false;
            $insertedTransactions['numberofteacherinserted'] = 0;
            $insertedTransactions['numberOfCreditsUsedTeachers'] = 0;
        }

        if (empty($insertedTransactions))
            return false;
        return $insertedTransactions;
    }

    /**
     * Returns all mobiles for a student in an array. (In memory operation)
     * @param $student
     * @return array
     */
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

    public function getFormattedMessage($studentCodes, $messageTemplate)
    {
        $messages = array();
        foreach ($studentCodes as $studentCode) {
            $code = $studentCode->code;
            $messages[$code] = $this->formatMessage($messageTemplate);
        }
        return $messages;
    }

    public function getFormattedMessageTeachers($teacherCodes, $messageTemplate)
    {
        $messages = array();
        foreach ($teacherCodes as $teacherCode) {
            $code = $teacherCode->code;
            $messages["$teacherCode"] = $this->formatMessage($messageTemplate);
        }
        return $messages;
    }

    public function getFormattedMessageDepartment($teacherCodes, $messageTemplate)
    {
        $messages = array();
        foreach ($teacherCodes as $teacherCode) {
            $code = $teacherCode->code;
            $messages["$code"] = $this->formatMessage($messageTemplate);
        }
        return $messages;
    }

    public function getTemplate($template_id)
    {
        return SMSTemplate::find($template_id);
    }

    protected function checkCreditsBalance($requiredCredits, $schoolId)
    {
        $availableCredits = $this->getRemainingCredits($schoolId);
        if ($availableCredits > $requiredCredits)
            return true;
        return false;
    }

}
