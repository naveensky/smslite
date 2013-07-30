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

    public static $rules = array(
        'name' => 'required',
        'email' => 'email',
        'id' => 'required'
    );

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
        if (empty($teacherCode))
            return 0;
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
            $query = $query->where_in(DB::raw('lower(department)'), $department);
        if (!empty($morningBusRoute))
            $query = $query->where_in("morningBusRoute", $morningBusRoute);
        if (!empty($eveningBusRoute))
            $query = $query->where_in("eveningBusRoute", $eveningBusRoute);

        try {
            $teacher = $query->skip($skip)->take($perPage)->order_by('id')->order_by('name')->get();
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
            $query = $query->where_in(DB::raw('lower(department)'), $department);
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

    /**getting distinct teachers from the bus routes
     * @param $morningBusRoutes
     * @param $eveningBusRoutes
     * @param $schoolId
     * @return bool
     */
    public function getTeachersFromBusRoutes($morningBusRoutes, $eveningBusRoutes, $schoolId)
    {

        $query = Teacher::where_schoolId($schoolId);

        $query = $query->where(function ($query) use ($morningBusRoutes, $eveningBusRoutes) {
            if (!empty($morningBusRoutes))
                $query->where_in("morningBusRoute", $morningBusRoutes);
            if (!empty($eveningBusRoutes))
                $query->or_where_in("eveningBusRoute", $eveningBusRoutes);

        });

        try {
            $teachers = $query->distinct('code')->get();
        } catch (Exception $e) {
            Log::exception($e);
            return false;
        }

        return $teachers;

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
        $query = $query->where_in(DB::raw('lower(department)'), $departments);
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
            $query->where('name', 'like', "%$searchValue%");
            for ($i = 1; $i <= 5; $i++) {
                $query->or_where("mobile$i", 'like', "%$searchValue%");
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

    public function addTeacher($teacherData, $schoolId)
    {
        $teacher = new Teacher();
        $teacher->name = $teacherData['name'];
        $teacher->email = $teacherData['email'];
        $teacher->mobile1 = $teacherData['mobile1'];
        $teacher->mobile2 = $teacherData['mobile2'];
        $teacher->mobile3 = $teacherData['mobile3'];
        $teacher->mobile4 = $teacherData['mobile4'];
        $teacher->mobile5 = $teacherData['mobile5'];
        $teacher->dob = $teacherData['dob'];
        $teacher->department = $teacherData['department'];
        $teacher->morningBusRoute = $teacherData['morningBusRoute'];
        $teacher->eveningBusRoute = $teacherData['eveningBusRoute'];
        $teacher->gender = $teacherData['gender'];
        $teacher->code = $teacherData['code'];
        $teacher->schoolId = $schoolId;

        try {
            $teacher->save();
        } catch (Exception $e) {
            Log::exception($e);
            return false;
        }
        return true;
    }

    private function isTeacherExist($importKey, $schoolId)
    {
        try {
            $teacher = Teacher::where('schoolId', '=', $schoolId)->where('importKey', '=', $importKey)->first();
        } catch (Exception $e) {
            return false;
        }
        return $teacher;
    }

    public function insertOrUpdate($teacherData, $schoolId)
    {
        if (is_array($teacherData) && !empty($teacherData)) {
            $teacherImportKeysWithErrors = array();
            $totalTeachersImported = 0;
            $totalTeachersUpdated = 0;
            foreach ($teacherData as $dataRow) {
                $input = array(
                    'id' => $dataRow->TeacherMaster->Id,
                    'name' => $dataRow->TeacherMaster->FullName,
                    'email' => $dataRow->TeacherMaster->EmailId
                );

                $validator = Validator::make($input, TeacherRepository::$rules);
                if ($validator->fails()) {

                    if ($validator->errors->has('name') || $validator->errors->has('id')) {
                        if (isset($input['id']))
                            $teacherImportKeysWithErrors[] = $input['id'];
                        continue;
                    }
                    if ($validator->errors->has('email')) {
                        $dataRow->TeacherMaster->EmailId = "";
                    }
                }
                $mobile1 = '';
                $mobile2 = '';
                $mobile3 = '';
                $mobile4 = '';
                $mobile5 = '';
                $mobile = $dataRow->TeacherMaster->MobileNo;
                if (empty($mobile)) {
                    if (isset($input['id']))
                        $teacherImportKeysWithErrors[] = $input['id'];
                    continue;
                }

                $splitMobilePhones = explode(',', $mobile);
                foreach ($splitMobilePhones as $mobileInfo) {
                    ltrim(str_replace('+91', '', trim($mobileInfo, ' ')), '0');
                    if (strlen($mobileInfo) != 10) {
                        continue;
                    }
                    for ($i = 1; $i <= 5; $i++) {
                        if (empty(${"mobile$i"})) {
                            ${"mobile$i"} = $mobileInfo;
                            break;
                        }

                    }
                }

                if (empty($mobile1)) {
                    if (isset($input['id']))
                        $teacherImportKeysWithErrors[] = $input['id'];
                    continue;
                }

                $name = isset($dataRow->TeacherMaster->FullName) ? $dataRow->TeacherMaster->FullName : '';
                $email = !empty($dataRow->TeacherMaster->EmailId) ? $dataRow->TeacherMaster->EmailId : '';
                $importKey = isset($dataRow->TeacherMaster->Id) ? $dataRow->TeacherMaster->Id : '';
                $dob = !empty($dataRow->TeacherMaster->DOB) ? $dataRow->TeacherMaster->DOB : null; //DOB
                $department = isset($dataRow->department) ? $dataRow->department : '';
                $morningBusRoute = isset($dataRow->morningBusRoute) ? $dataRow->morningBusRoute : '';
                $eveningBusRoute = isset($dataRow->eveningBusRoute) ? $dataRow->eveningBusRoute : '';

                if (isset($dataRow->TeacherMaster->Gender)) {
                    $gender = 'Female';
                    if ($dataRow->TeacherMaster->Gender == true || $dataRow->TeacherMaster->Gender = 'true')
                        $gender = 'Male';
                } else
                    $gender = '';
                $teacherExist = $this->isTeacherExist($importKey, $schoolId);
                if (!is_null($teacherExist) && $teacherExist == false)
                    $teacherImportKeysWithErrors[] = $importKey;
                else if (is_null($teacherExist)) {
                    try {
                        $teacher = new Teacher();
                        $teacher->name = $name;
                        $teacher->email = $email;
                        $teacher->mobile1 = $mobile1;
                        $teacher->mobile2 = $mobile2;
                        $teacher->mobile3 = $mobile3;
                        $teacher->mobile4 = $mobile4;
                        $teacher->mobile5 = $mobile5;
                        $teacher->department = $department;
                        $teacher->importKey = $importKey;
                        $teacher->morningBusRoute = $morningBusRoute;
                        $teacher->eveningBusRoute = $eveningBusRoute;
                        $teacher->gender = $gender;
                        $teacher->dob = $dob;
                        $teacher->schoolId = $schoolId;
                        $teacher->code = Str::random(64, 'alpha');
                        $teacher->save();
                        $totalTeachersImported += 1;
                    } catch (Exception $e) {
                        Log::exception($e);
                        $teacherImportKeysWithErrors[] = $importKey;
                    }
                } else {
                    try {

                        Teacher::update($teacherExist->id, array('name' => $name, 'email' => $email,
                            'mobile1' => $mobile1, 'mobile2' => $mobile2, 'mobile3' => $mobile3, 'mobile4' => $mobile4,
                            'mobile5' => $mobile5, 'morningBusRoute' => $morningBusRoute, 'eveningBusRoute' => $eveningBusRoute,
                            'gender' => $gender, 'department' => $department));
                        $totalTeachersUpdated += 1;
                    } catch (Exception $e) {
                        Log::exception($e);
                        $teacherImportKeysWithErrors[] = $importKey;
                    }
                }

            }
            return array('status' => true, 'teachersImported' => $totalTeachersImported, 'teachersUpdated' => $totalTeachersUpdated, 'importErrors' => $teacherImportKeysWithErrors);
        }
    }
}
