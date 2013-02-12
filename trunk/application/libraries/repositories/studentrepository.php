<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 1/21/13
 * Time: 1:46 PM
 * To change this template use File | Settings | File Templates.
 */
class StudentRepository
{

    /**
    /**
     * @param $schoolCode - Code for school to which student needs to be added
     * @param $students - array containing data for students
     *
     * @throws InvalidArgumentException
     * @return array|bool
     */

//    public static $hidden = array('id'); //to exclude id from the result
    public function createStudents($schoolCode, $students)
    {
        $school = School::where_code($schoolCode)->get();

        //if no school is found for given code, return not found.

        if (empty($school)) {
            throw new InvalidArgumentException("Invalid School Code $schoolCode");
        }

        //todo: insert in bulk
        $insertedStudents = array();
        foreach ($students as $student) {
            try {
                $student->schoolId = $school[0]->id;
                $student->save();
                $insertedStudents[] = $student->attributes;
            } catch (Exception $e) {
                Log::exception($e);
            }
        }
        return $insertedStudents;
    }

    public function updateStudent($studentCode, $update_data)
    {
        $student = Student::where_code($studentCode)->get();

        //if no student is found for given code, return not found.
        if (empty($student)) {
            throw new InvalidArgumentException("Invalid Student Code $studentCode");
        }

        try {
            $id = $student[0]->id;
            Student::update($id, $update_data);
        } catch (Exception $e) {
            Log::exception($e);
            return false;
        }
        return $student;
    }

    public function getStudent($studentCode)
    {
        return Student::where_code($studentCode)->get();
    }

    public function deleteStudent($studentCode)
    {
        $deleteCount = Student::where_code($studentCode)->delete();
        return $deleteCount > 0;
    }


    public function getStudentsFromCode($students_codes)
    {
        $students = Student::where_in('code', $students_codes)->get();
        return $students;
    }

}
