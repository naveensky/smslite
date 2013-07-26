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

//        foreach ($this->knownVariables as $key => $value) {
        $template = preg_replace_callback('/<%[^%>]*%>/',
            function ($match) {
                if (preg_match('/<%text_[^%>]*%>/', $match[0]))
                    return $match[0];
                return Util::getFormatSMSTemplate($match[0]);
            },
            $template
        );
//        }
        $template = preg_replace_callback('/<%text_[^%>]*%>/',
            function ($match) {
                return Util::getFormattedTemplate(str_replace('text_', '$text_', $match[0]));
            },
            $template
        );
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
                $data['name'] = isset($student->name) ? $student->name : ''; //name
                $data['dob'] = isset($student->dob) ? $student->dob : ''; //dob
                $classStandard = isset($student->classStandard) ? $student->classStandard : '';
                $classSection = isset($student->classSection) ? ucfirst($student->classSection) : '';
                $data['class'] = $classStandard . '-' . $classSection;
                $data['section'] = isset($student->classSection) ? $student->classSection : '';
                $data['mornigbusroute'] = isset($student->morningBusRoute) ? $student->morningBusRoute : '';
                $data['eveningbusroute'] = isset($student->eveningBusRoute) ? $student->eveningBusRoute : '';
                $data['today'] = date('d M Y');
                $completeMessage = View::make($fileName, $data)->render();
                $studentsData[$student->code] = $completeMessage;
            }
        }

        if (!empty($teachers)) {
            foreach ($teachers as $teacher) {
                $data['name'] = isset($teacher->name) ? $teacher->name : '';
                $data['dob'] = isset($teacher->dob) ? $teacher->dob : '';
                $data['department'] = isset($teacher->department) ? $teacher->department : '';
                $data['mornigbusroute'] = isset($teacher->morningBusRoute) ? $teacher->morningBusRoute : '';
                $data['eveningbusroute'] = isset($teacher->eveningBusRoute) ? $teacher->eveningBusRoute : '';
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