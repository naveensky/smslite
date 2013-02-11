<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 1/22/13
 * Time: 6:36 PM
 * To change this template use File | Settings | File Templates.
 */
class SchoolRepository
{
    public function createSchool($school_new)
    {
        $school_obj = new School();
        $school_obj->name = $school_new->name;
        $school_obj->address = $school_new->address;
        $school_obj->city = $school_new->city;
        $school_obj->state = $school_new->state;
        $school_obj->zip = $school_new->zip;
        $school_obj->senderId = $school_new->senderId;
        $school_obj->contactPerson = $school_new->contactPerson;
        $school_obj->contactMobile = $school_new->contactMobile;
        $school_obj->code = Str::random(64, 'alpha');
        try {
            $school_obj->save();

        } catch (Exception $e) {
            Log::exception($e);
            return false;
        }

        return $school_obj;

    }

    public function updateSchool($schoolCode, $update_data)
    {

        $school = School::where_code($schoolCode)->get();

        if (empty($school[0])) {
            throw new InvalidArgumentException("Invalid School Code $schoolCode");
        }

        try {
            $id = $school[0]->id;
            School::update($id, $update_data);
        } catch (Exception $e) {
            Log::exception($e);
            return false;
        }

        return $school;
    }


    public function getSchool($schoolCode)
    {
        return School::where_code($schoolCode)->get();

    }

    public function deleteSchool($schoolCode)
    {
        $deleteCount = School::where_code($schoolCode)->delete();
        return $deleteCount > 0;
    }

    public function checkSchoolCode($schoolCode)
    {
        $school = School::where_code($schoolCode)->get();
        if (empty($school[0])) {
            throw new InvalidArgumentException("Invalid School Code $schoolCode");
        }
    }

    public function createEmptySchool()
    {
        $school_obj = new School();
        $school_obj->name = "";
        $school_obj->address = "";
        $school_obj->city = "";
        $school_obj->state = "";
        $school_obj->zip = "";
        $school_obj->senderId = "";
        $school_obj->contactPerson = "";
        $school_obj->contactMobile = "";
        $school_obj->code = Str::random(64, 'alpha');

        try {
            $school_obj->save();
        } catch (Exception $e) {
            Log::exception($e);
            return false;
        }

        return $school_obj;

    }
}
