<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 3/8/13
 * Time: 12:37 PM
 * To change this template use File | Settings | File Templates.
 */

class MessageParser
{
    private $knownVariables = array();

    public function getVariables($template)
    {
        $pattern = '/<%[^%>]*%>/'; //pattern to find the placeholder inside the message
        preg_match_all($pattern, $template, $matches); //return array of placeholder within the message
        $matches = $matches[0]; //matches contain array of array so to get first array
        $messageVars = array(); //array containing key value pair array
        foreach ($matches as $match) {
            $key = preg_replace(array('/<%/', '/%>/'), ' ', $match);
            $value = explode("-", $key);
            $value = ucfirst($value[1]) . ' ' . ucfirst($value[2]);
            $messageVar["$key"] = $value;
        }
        return $messageVars;
    }

    //returning key value pair of studentCode=>message and teacherCode=>message
    public function parseTemplate($template, $students, $teachers, $messageVars)
    {
        $filePath = path('app') . 'views/'; //directory to make temporary files
        $template = str_replace('text_', '$text_', $template);
        $studentsData = array();
        $teachersData = array();
        if (!empty($students)) {
            //setting default variables value
            $data = array();
            foreach ($messageVars as $key => $val) {
                $data[trim($key)] = $val;
            }
            $fileName = 'tmp/'.Str::lower(Str::random(64, 'alpha'));

            File::put($filePath . $fileName . '.blade.php', $template);
            foreach ($students as $student) {
                $data['student_name'] = $student->name;
                $data['DOB'] = $student->dob;
                $data['class'] = $student->classStandard;
                $data['section'] = $student->classSection;
                $completeMessage = View::make($fileName, $data)->render();
                $studentsData["$student->code"] = $completeMessage;
            }
            File::delete($filePath . $fileName.'.blade.php');
        }

        if (!empty($teachers)) {
            //setting default variables value
            $data = array();
            foreach ($messageVars as $key => $val) {
                $data[trim($key)] = $val;
            }
            $fileName = 'tmp/'.Str::lower(Str::random(64, 'alpha'));

            File::put($filePath . $fileName . '.blade.php', $template);
            foreach ($teachers as $teacher) {
                $data['teacher_name'] = $teacher->name;
                $data['DOB'] = $teacher->dob;
                $data['department'] = $teacher->department;
                $completeMessage = View::make($fileName, $data)->render();
                $teachersData["$teacher->code"] = $completeMessage;
            }
            File::delete($filePath . $fileName.'.blade.php');
        }
        $result = array('students' => $studentsData, 'teachers' => $teachersData);
        return $result;

    }
}