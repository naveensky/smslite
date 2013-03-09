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


    public function getTeachersFromCodes($teachers_codes)
    {
        if (empty($teachers_codes))
            return array();

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
            Log::exception($e);
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

    public function getTeachersToExport($department, $morningBusRoute, $eveningBusRoute)
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
            $teacher = $query->get();
        } catch (Exception $e) {
            log::exception($e);
            return false;
        }
        return $teacher;
    }

    public function getTeacherCodeFromBusRoutes($morningBusRoutes, $eveningBusRoutes)
    {
        $schoolId = Auth::user()->schoolId;
        $query = Teacher::where_schoolId($schoolId);

        $query = $query->where(function ($query) use ($morningBusRoutes, $eveningBusRoutes) {
            if (!empty($morningBusRoutes))
                $query->where_in("morningBusRoute", $morningBusRoutes);
            if (!empty($eveningBusRoutes))
                $query->or_where_in("eveningBusRoute", $eveningBusRoutes);

        });
        $codes = $query->distinct('code')->get('code');
        $teacherCodes = array();
        foreach ($codes as $code) {
            $teacherCodes[] = $code->code;
        }
        return $teacherCodes;
    }

    public function getTeacherCodeFromDepartments(array $departments)
    {
        $schoolId = Auth::user()->schoolId;
        $query = Teacher::where('schoolId', '=', $schoolId);
        $query = $query->where_in(DB::raw('lower("department")'), $departments);
        $codes = $query->distinct('code')->get('code');
        $teacherCodes = array();
        foreach ($codes as $code) {
            $teacherCodes[] = $code->code;
        }
        return $teacherCodes;
    }

    public function getTeacherByNameOrMobile($schoolId, $searchValue)
    {
        $query = Teacher::where_schoolId($schoolId)->where(function ($query) use ($searchValue) {
            $query->where('name', '~*', ".*$searchValue.*");
            for($i=1;$i<=5;$i++){
                $query->or_where("mobile$i", '~*', ".*$searchValue.*");
            }
        });
        try {
            $teachers = $query->get();
        } catch (Exception $e) {
            Log::exception($e);
            return false;
        }
        return $teachers;
    }

}
