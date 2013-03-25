<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 1/21/13
 * Time: 11:56 AM
 * To change this template use File | Settings | File Templates.
 */
class Teacher extends Eloquent
{

    public static $hidden = array('id'); //to exclude id from the result
    public static $rules = array(
        'name' => 'required',
        'email' => 'email',
        'mobile1' => 'required|max:10'
    );

    public function school()
    {
        return $this->belongs_to('School');
    }

    public static function parseFromCSV($csvData, $skipHeaderRow = true)
    {
        $data = array_map("str_getcsv", preg_split('/\r*\n+|\r+/', $csvData));
        $BulkTeachers = array();
        $errorRows = array(); //array containing the row numbers of the csv containg errors
        $errorRowCount = 1; //if do not want to skip header row from the csv
        if ($skipHeaderRow) {
            $errorRowCount = 2; //skip the header row
            unset($data[0]);
        }
        //get the current logined user id and find the school Id from that
        $schoolId = Auth::user()->schoolId;

        foreach ($data as $dataRow) {
            $input = array(
                'name' => $dataRow[0],
                'email' => $dataRow[3],
                'mobile1' => $dataRow[4]

            );

            $validator = Validator::make($input, static::$rules);
            if ($validator->fails()) {

                if ($validator->errors->has('name') || $validator->errors->has('mobile1')) {
                    $errorRows[] = $errorRowCount;
                    $errorRowCount++;
                    continue;
                }
                if ($validator->errors->has('email')) {
                    $dataRow[3] = "";
                }

            }
            $insertRow['name'] = isset($dataRow[0]) ? $dataRow[0] : ""; //Full Name
            $insertRow['department'] = isset($dataRow[1]) ? $dataRow[1] : ""; //Department
            $insertRow['gender'] = isset($dataRow[2]) ? $dataRow[2] : ""; //Gender
            $insertRow['email'] = $dataRow[3];
            $insertRow['mobile1'] = isset($dataRow[4]) ? $dataRow[4] : ""; //Mobile1
            $insertRow['mobile2'] = isset($dataRow[5]) ? $dataRow[5] : ""; //Mobile2
            $insertRow['mobile3'] = isset($dataRow[6]) ? $dataRow[6] : ""; //Mobile3
            $insertRow['mobile4'] = isset($dataRow[7]) ? $dataRow[7] : ""; //Mobile4
            $insertRow['mobile5'] = isset($dataRow[8]) ? $dataRow[8] : ""; //Mobile5
            $insertRow['dob'] = isset($dataRow[9]) ? $dataRow[9] : ""; //DOB
            $insertRow['morningBusRoute'] = isset($dataRow[10]) ? $dataRow[10] : ""; //Morning Bus Route
            $insertRow['eveningBusRoute'] = isset($dataRow[11]) ? $dataRow[11] : ""; //Evening Bus Route
            $insertRow['code'] = Str::random(64, 'alpha'); //student Code
            $insertRow['schoolId'] = $schoolId; //school Id
            $insertRow['created_at'] = 'Now';
            $insertRow['updated_at'] = 'Now';
            $BulkTeachers[] = $insertRow;
            $errorRowCount++;
        }

        return array('bulkTeachers' => $BulkTeachers, 'errorRows' => $errorRows);
    }

    public static function parseToCSV($teachers)
    {

        $teachersData = array();

        foreach ($teachers as $teacher) {
            $row = array();
            $row['name'] = $teacher->name;
            $row['department'] = $teacher->department;
            $row['gender'] = $teacher->gender;
            $row['email'] = $teacher->email;
            $row['mobile1'] = $teacher->mobile1;
            $row['mobile2'] = $teacher->mobile2;
            $row['mobile3'] = $teacher->mobile3;
            $row['mobile4'] = $teacher->mobile4;
            $row['mobile5'] = $teacher->mobile5;
            $row['dob'] = $teacher->dob;
            $row['morningBusRoute'] = $teacher->morningBusRoute;
            $row['eveningBusRoute'] = $teacher->eveningBusRoute;
            array_push($teachersData, $row);
        }

        $csvData = "";
        $headerRow = "Full Name,Department,Gender,Email,Mobile1,Mobile2,Mobile3,Mobile4,Mobile5,DOB,Morning Bus Route,Evening Bus Route \n";
        $csvData .= $headerRow;
        foreach ($teachersData as $data) {
            $dataRow = "";
            foreach ($data as $key => $value) {
                $dataRow .= "\"$value\",";
            }
            $dataRow = rtrim($dataRow, ",");
            $csvData .= "$dataRow \n";
        }
        return $csvData;
    }

    public static $factory = array(
        'name' => 'string',
        'email' => 'email',
        'mobile1' => 'string',
        'mobile2' => 'string',
        'mobile3' => 'string',
        'mobile4' => 'string',
        'mobile5' => 'string',
        'dob' => '1 jan 2013',
        'department' => 'string',
        'morningBusRoute' => 'string',
        'eveningBusRoute' => 'string',
        'code' => 'string',
        'gender' => 'string',
        'schoolId' => 'factory|School',
    );
}
