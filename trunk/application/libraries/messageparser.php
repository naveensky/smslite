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
    public $knownVariables = array(
        'name' => 'Person name to whom message to be send',
        'class' => 'Student Class',
        'section' => 'Student Section',
        'morningbusroute' => 'Morning Bus Route',
        'eveningbusroute' => 'Evening Bus Route',
        'department' => 'Teacher Department',
        'dob' => 'Person date of birth to whom message to be send',
        'today' => 'Today Date'
    );

    public function getVariables($template)
    {
        //pattern to find the placeholder inside the message
        $pattern = '/<%text_[^%>]*%>/';

        //return array of placeholder within the message
        preg_match_all($pattern, $template, $matches);
        //matches contain array of array so to get first array
        $matches = $matches[0];
        //array containing key value pair array
        $messageVars = array();

        foreach ($matches as $match) {
            //extract key name from template
            $key = preg_replace(array('/<%/', '/%>/'), ' ', $match);
            $value = explode("_", $key);
            //create variable name from key
            $data = '';
            for ($i = 1; $i < count($value); $i++) {
                $data .= ucfirst($value[$i]) . ' ';
            }
            $messageVars[trim($key)] = trim($data);
        }
        return $messageVars;
    }


    /**
     * Returns key value pair of
     * studentCode=>message
     * teacherCode=>message
     *
     * @param $template
     * @param $students
     * @param $teachers
     * @param $messageVars
     * @return array
     */

    public function parseTemplate($template, $students, $teachers, $messageVars)
    {
        $viewsDirectory = path('app') . 'views/'; //directory to make temporary files
        $template = preg_replace_callback('/<%[^%>]*%>/',
            function ($match) {
                return str_replace('text_', '$text_', $match[0]);
            },
            $template
        );

        foreach ($this->knownVariables as $key => $value) {
            $template = preg_replace_callback('/<%[^%>]*%>/',
                function ($match) use ($key, $value) {
                    return str_replace($key, '$' . $key, $match[0]);
                },
                $template
            );
        }
        $studentsData = array();
        $teachersData = array();

        //setting default variables value
        $data = array();
        foreach ($messageVars as $key => $val) {
            $data[trim($key)] = $val;
        }

        //create file for storing template content and render it via blade template engine
        $fileName = 'tmp/' . Str::lower(Str::random(64, 'alpha'));
        File::put($viewsDirectory . $fileName . '.blade.php', $template);

        if (!empty($students)) {

            foreach ($students as $student) {
                $data['name'] = $student->name; //name
                $data['dob'] = $student->dob; //dob
                $data['class'] = $student->classStandard . '-' . ucfirst($student->classSection);
                $data['section'] = $student->classSection;
                $data['mornigbusroute'] = $student->morningBusRoute;
                $data['eveningbusroute'] = $student->eveningBusRoute;
                $data['today'] = date('d M Y');
                $completeMessage = View::make($fileName, $data)->render();
                $studentsData[$student->code] = $completeMessage;
            }
        }

        if (!empty($teachers)) {
            foreach ($teachers as $teacher) {
                $data['name'] = $teacher->name;
                $data['dob'] = $teacher->dob;
                $data['department'] = $teacher->department;
                $data['mornigbusroute'] = $teacher->morningBusRoute;
                $data['eveningbusroute'] = $teacher->eveningBusRoute;
                $data['today'] = date('d M Y');
                $completeMessage = View::make($fileName, $data)->render();
                $teachersData[$teacher->code] = $completeMessage;
            }
        }
        //delete the temporary view file
        File::delete($viewsDirectory . $fileName . '.blade.php');
        if (count($studentsData) == 0 && count($teachersData) == 0)
            return array();
        $result = array('studentsCode' => $studentsData, 'teachersCode' => $teachersData);
        return $result;
    }
}