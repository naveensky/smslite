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


    /**
     * @param $students_codes
     * @return array
     */
    public function getStudentsFromCode($students_codes)
    {
        if (empty($students_codes))
            return array();
        $students = Student::where_in('code', $students_codes)->get();
        return $students;
    }

    public function bulkStudentsInsert($bulkStudents)
    {

        try {
            //using database transaction
            DB::connection()->pdo->beginTransaction();
            $statusStudents = Student::insert($bulkStudents);
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

    //function to get the class from the given classSection
    public function getClass($classSection)
    {
        return strstr($classSection, '-', true);
    }

    public function getSection($classSection)
    {
        return preg_replace('/[^-]*-/', "", $classSection);
    }

    public function getStudentCodes($classSections)
    {
        $schoolId = Auth::user()->schoolId;
        $query = Student::where('schoolId', '=', $schoolId);

        $count = 1; //count is used for making the query
        foreach ($classSections as $classSection) {
            $class = $this->getClass($classSection); //getting class from classSection
            $section = $this->getSection($classSection); //getting section from classSection
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
            $count++;
        }
        try {
            $codes = $query->get('code');
        } catch (Exception $e) {
            Log::exception($e);
            return false;
        }

        $studentCodes = array();
        foreach ($codes as $code) {
            $studentCodes[] = $code->code;
        }

        return $studentCodes;
    }

    public function getStudents($classSections, $morningBusRoute, $eveningBusRoute, $perPage, $skip)
    {
        $schoolId = Auth::user()->schoolId;
        $query = Student::where('schoolId', '=', $schoolId);
        if (!empty($classSections)) {
            $count = 1;
            foreach ($classSections as $classSection) {
                $class = strstr($classSection, '-', true);
                $section = preg_replace('/[^-]*-/', "", $classSection);
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
                $count++;
            }
        }
        if (!empty($morningBusRoute))
            $query = $query->where_in("morningBusRoute", $morningBusRoute);
        if (!empty($eveningBusRoute))
            $query = $query->where_in("eveningBusRoute", $eveningBusRoute);

        try {
            $student = $query->skip($skip)->take($perPage)->get();
        } catch (Exception $e) {
            Log::exception($e);
            return false;
        }

        return $student;
    }


    public function getStudentsToExport($classSections, $morningBusRoute, $eveningBusRoute)
    {
        $schoolId = Auth::user()->schoolId;
        $query = Student::where('schoolId', '=', $schoolId);
        if (!empty($classSections)) {
            $count = 1;
            foreach ($classSections as $classSection) {
                $class = strstr($classSection, '-', true);
                $section = preg_replace('/[^-]*-/', "", $classSection);
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
                $count++;
            }
        }
        if (!empty($morningBusRoute))
            $query = $query->where_in("morningBusRoute", $morningBusRoute);
        if (!empty($eveningBusRoute))
            $query = $query->where_in("eveningBusRoute", $eveningBusRoute);

        try {
            $student = $query->get();
        } catch (Exception $e) {
            log::exception($e);
            return false;
        }

        return $student;
    }

    public function getStudentCodeFromBusRoutes($morningBusRoutes, $eveningBusRoutes)
    {
        $schoolId = Auth::user()->schoolId;
        $query = Student::where_schoolId($schoolId);
        $query = $query->where(function ($query) use ($morningBusRoutes, $eveningBusRoutes) {
            if (!empty($morningBusRoutes))
                $query->where_in("morningBusRoute", $morningBusRoutes);
            if (!empty($eveningBusRoutes))
                $query->or_where_in("eveningBusRoute", $eveningBusRoutes);

        });
        $codes = $query->distinct('code')->get('code');

        $studentCodes = array();
        foreach ($codes as $code) {
            $studentCodes[] = $code->code;
        }
        return $studentCodes;
    }


}
