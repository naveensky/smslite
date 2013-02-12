<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 1/21/13
 * Time: 2:07 PM
 * To change this template use File | Settings | File Templates.
 */
class TeacherRepository
{

    public function createTeachers($schoolCode, $teachers)
    {
        $school = School::where_code($schoolCode)->get();

        //if no school is found for given code, return not found.
        if (empty($school)) {
            throw new InvalidArgumentException("Invalid School Code $schoolCode");
        }

        $insertedTeachers = array();
        foreach ($teachers as $teacher) {
            try {
                $teacher->schoolId = $school[0]->id;
                $teacher->save();
                $insertedTeachers[]=$teacher->attributes;
            } catch (Exception $e) {
                Log::exception($e);
            }
        }
        return $insertedTeachers;
    }


    public function updateTeacher($teacherCode,$update_data)
    {
        $teacher = Teacher::where_code($teacherCode)->get();
        if (empty($teacher)) {
            throw new InvalidArgumentException("Invalid Teacher Code $teacherCode");
        }

        try {
            $id = $teacher[0]->id;
            Teacher::update($id, $update_data);
        } catch (Exception $e) {
            Log::exception($e);
            return false;
        }

        return $teacher;

    }

    public function getTeacher($teacherCode)
    {
        return Teacher::where_code($teacherCode)->get();
    }

    public function deleteTeacher($teacherCode)
    {
        $deleteCount = Teacher::where_code($teacherCode)->delete();
        return $deleteCount > 0;
    }


    public function getTeachersFromCode($teachers_codes)
    {
        $teachers = Teacher::where_in('code', $teachers_codes)->get();
        return $teachers;
    }

}
