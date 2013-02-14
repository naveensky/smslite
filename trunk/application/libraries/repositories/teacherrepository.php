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
                $insertedTeachers[] = $teacher->attributes;
            } catch (Exception $e) {
                Log::exception($e);
            }
        }
        return $insertedTeachers;
    }


    public function updateTeacher($teacherCode, $update_data)
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

    public function bulkTeachersInsert($bulkTeachers)
    {

        try {
            //using database transaction
            DB::connection()->pdo->beginTransaction();
            $statusTeachers = Teacher::insert($bulkTeachers);
            DB::connection()->pdo->commit();
        } catch (PDOException $e) {
            //rollback if any error while bulk insertion
            DB::connection()->pdo->rollBack();
            log::exception($e);
            throw new PDOException("Exception while bulk insertion");
        } catch (Exception $e) {
            log::exception($e);
            return false;
        }

        return true;
    }

    public function getTeachers($department, $morningBusRoute, $eveningBusRoute, $perPage, $skip)
    {
        $schoolId = Auth::user()->schoolId;
        $query = Teacher::where('schoolId', '=', $schoolId);
        if (!empty($department))
            $query = $query->where_in(DB::raw('lower("department")'), $department);
        if (!empty($morningBusRoute))
            $query = $query->where_in("morningBusRoute", $morningBusRoute);
        if (!empty($eveningBusRoute))
            $query = $query->where_in("eveningBusRoute", $eveningBusRoute);

        try {
            $teacher = $query->skip($skip)->take($perPage)->get();
        } catch (Exception $e) {
            log::exception($e);
            return false;
        }
        return $teacher;
    }

    public function getDepartments()
    {
        $schoolId = Auth::user()->schoolId;
        $departments=DB::query('SELECT DISTINCT lower("department") as department FROM "teachers" WHERE "schoolId" = ' . $schoolId );
        $departmentsData = array();
        foreach ($departments as $department) {
            $departmentsData[] = $department->department;
        }
        return $departmentsData;
    }

    public function getMorningBusRoutes()
    {
        $schoolId = Auth::user()->schoolId;
        $query = Teacher::where('schoolId', '=', $schoolId);
        $query = $query->distinct('morningBusRoute');
        $routes = $query->get('morningBusRoute');
        $morningRoutes = array();
        foreach ($routes as $route) {
            $morningRoutes[] = $route->morningBusRoute;
        }
        return $morningRoutes;
    }

    public function getEveningBusRoutes()
    {
        $schoolId = Auth::user()->schoolId;
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
