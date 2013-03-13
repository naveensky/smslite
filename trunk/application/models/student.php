<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 1/17/13
 * Time: 1:12 PM
 * To change this template use File | Settings | File Templates.
 */
class Student extends Eloquent
{
    public static $hidden = array('id'); //to exclude id from the result

    public function school()
    {
        return $this->belongs_to('School');
    }

    public static $rules = array(
        'name' => 'required',
        'email' => 'email',
        'mobile1' => 'required|max:10'
    );

    public static function parseFromCSV($csvData, $skipHeaderRow = true)
    {
        $data = array_map("str_getcsv", preg_split('/\r*\n+|\r+/', $csvData));
        $BulkStudents = array();
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
                'email' => $dataRow[6],
                'mobile1' => $dataRow[7]

            );

            $validator = Validator::make($input, static::$rules);
            if ($validator->fails()) {

                if ($validator->errors->has('name') || $validator->errors->has('mobile1')) {
                    $errorRows[] = $errorRowCount;
                    $errorRowCount++;
                    continue;
                }
                if ($validator->errors->has('email')) {
                    $dataRow[6] = "";
                }

            }
            $insertRow['name'] = isset($dataRow[0]) ? $dataRow[0] : ""; //Full Name
            $insertRow['classStandard'] = isset($dataRow[1]) ? $dataRow[1] : ""; //Class Standard
            $insertRow['classSection'] = isset($dataRow[2]) ? $dataRow[2] : ""; //Class Section
            $insertRow['sex'] = isset($dataRow[3]) ? $dataRow[3] : ""; //Gender
            $insertRow['fatherName'] = isset($dataRow[4]) ? $dataRow[4] : ""; //Father Name
            $insertRow['motherName'] = isset($dataRow[5]) ? $dataRow[5] : ""; //Mother Name
            $insertRow['email'] = $dataRow[6];
            $insertRow['mobile1'] = isset($dataRow[7]) ? $dataRow[7] : ""; //Mobile1
            $insertRow['mobile2'] = isset($dataRow[8]) ? $dataRow[8] : ""; //Mobile2
            $insertRow['mobile3'] = isset($dataRow[9]) ? $dataRow[9] : ""; //Mobile3
            $insertRow['mobile4'] = isset($dataRow[10]) ? $dataRow[10] : ""; //Mobile4
            $insertRow['mobile5'] = isset($dataRow[11]) ? $dataRow[11] : ""; //Mobile5
            $insertRow['dob'] = isset($dataRow[12]) ? $dataRow[12] : ""; //DOB
            $insertRow['morningBusRoute'] = isset($dataRow[13]) ? $dataRow[13] : ""; //Morning Bus Route
            $insertRow['eveningBusRoute'] = isset($dataRow[14]) ? $dataRow[14] : ""; //Evening Bus Route
            $insertRow['code'] = Str::random(64, 'alpha'); //student Code
            $insertRow['schoolId'] = $schoolId; //school Id
            $insertRow['created_at'] = 'Now';
            $insertRow['updated_at'] = 'Now';
            $BulkStudents[] = $insertRow;
            $errorRowCount++;
        }

        return array('bulkStudents' => $BulkStudents, 'errorRows' => $errorRows);
    }

    public static function parseToCSV($students)
    {

        $studentsData = array();

        foreach ($students as $student) {
            $row = array();
            $row['name'] = $student->name;
            $row['classStandard'] = $student->classStandard;
            $row['classSection'] = $student->classSection;
            $row['sex'] = $student->sex;
            $row['fatherName'] = $student->fatherName;
            $row['motherName'] = $student->motherName;
            $row['email'] = $student->email;
            $row['mobile1'] = $student->mobile1;
            $row['mobile2'] = $student->mobile2;
            $row['mobile3'] = $student->mobile3;
            $row['mobile4'] = $student->mobile4;
            $row['mobile5'] = $student->mobile5;
            $row['dob'] = $student->dob;
            $row['morningBusRoute'] = $student->morningBusRoute;
            $row['eveningBusRoute'] = $student->eveningBusRoute;
            array_push($studentsData, $row);
        }

        $csvData = "";
        $headerRow = "Full Name,Class Standard,Class Section,Gender,Father Name,Mother Name,Email,Mobile1,Mobile2,Mobile3,Mobile4,Mobile5,DOB,Morning Bus Route,Evening Bus Route \n";
        $csvData .= $headerRow;
        foreach ($studentsData as $data) {
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
        'motherName' => 'string',
        'fatherName' => 'string',
        'mobile1' => 'string',
        'mobile2' => 'string',
        'mobile3' => 'string',
        'mobile4' => 'string',
        'mobile5' => 'string',
        'dob' => '1 jan 2013',
        'classStandard' => 'string',
        'classSection' => 'string',
        'morningBusRoute' => 'string',
        'eveningBusRoute' => 'string',
        'code' => 'string',
        'sex' => 'string',
        'schoolId' => 'factory|School',
    );
}
