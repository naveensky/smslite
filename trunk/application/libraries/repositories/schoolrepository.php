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

    public function getClassesWithStrength()
    {
        $schoolId = Auth::user()->schoolId;

        $classes = DB::query('select "classSection", "classStandard" from students where "schoolId"=' . $schoolId . ' group by "classSection", "classStandard"');
        $classSection = array();
        foreach ($classes as $class) {
            $classWise = array();
            $classWise['noofstudents'] = $this->countStudents($class->classStandard, $class->classSection);
            $classWise["$class->classStandard-$class->classSection"] = ucfirst($class->classStandard) . '-' . ucfirst($class->classSection);
            $classSection[] = $classWise;
        }
        return $classSection;
    }

    public function countStudents($class, $section)
    {
        $schoolId = Auth::user()->schoolId;
        $students = Student::where_schoolId_and_classStandard_and_classSection($schoolId, $class, $section)->count();
        return $students;
    }

    public function getClasses($schoolId)
    {
        //todo: convert this to eloquent expression
        $classes = DB::query('select "classSection", "classStandard" from students where "schoolId"=' . $schoolId . ' group by "classSection", "classStandard"');
        $data = array();
        foreach ($classes as $class) {
            $data[] = ucfirst($class->classStandard) . '-' . ucfirst($class->classSection);
        }
        return $data;
    }

    public function getMorningBusRoutes($schoolId)
    {
        $query = Student::where('schoolId', '=', $schoolId);
        $query = $query->distinct('morningBusRoute');
        $routes = $query->get('morningBusRoute');
        $morningRoutes = array();
        foreach ($routes as $route) {
            $morningRoutes[] = $route->morningBusRoute;
        }
        return $morningRoutes;
    }

    public function getEveningBusRoutes($schoolId)
    {
        $query = Student::where('schoolId', '=', $schoolId);
        $query = $query->distinct('eveningBusRoute');
        $routes = $query->get('eveningBusRoute');
        $eveningRoutes = array();
        foreach ($routes as $route) {
            $eveningRoutes[] = $route->eveningBusRoute;
        }
        return $eveningRoutes;
    }

    public function getDepartments($schoolId)
    {
        //todo: convert this to eloquent expression
        $departments = DB::query('SELECT DISTINCT lower("department") as department FROM "teachers" WHERE "schoolId" = ' . $schoolId);
        $departmentsData = array();
        foreach ($departments as $department) {
            $departmentsData[] = $department->department;
        }
        return $departmentsData;
    }

    public function getMorningBusRoutesOfTeachers($schoolId)
    {
        $query = Teacher::where('schoolId', '=', $schoolId);
        $query = $query->distinct('morningBusRoute');
        $routes = $query->get('morningBusRoute');
        $morningRoutes = array();
        foreach ($routes as $route) {
            $morningRoutes[] = $route->morningBusRoute;
        }
        return $morningRoutes;
    }

    public function getEveningBusRoutesOfTeachers($schoolId)
    {
        $query = Teacher::where('schoolId', '=', $schoolId);
        $query = $query->distinct('eveningBusRoute');
        $routes = $query->get('eveningBusRoute');
        $eveningRoutes = array();
        foreach ($routes as $route) {
            $eveningRoutes[] = $route->eveningBusRoute;
        }
        return $eveningRoutes;
    }
}
