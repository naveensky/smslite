<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 14/3/13
 * Time: 11:18 AM
 * To change this template use File | Settings | File Templates.
 */

class SMSTemplate_Task
{

    public function run($arguments)
    {
        $templates = array(

            'Absentee' => 'This is to inform you that your ward <%name%>  of class <%class%>  is not present in school today. We hope all is well',
            'Donation' => 'Kindly donate old clothes, stationery items,toys,books etc towards donation for underprivileged children. We look forward for your whole hearted co-operation',
            'Retest' => 'Your ward <%name%> of class <%class%> is required to appear for a retest in <%text_subject_name%> on <%text_date%>',
            'Misbehave' => 'Your ward <%name%> of class <%class%> was misbehaving in the school bus. Please counsel him/her not to repeat this.',
            'Late' => 'Your ward <%name%> of class <%class%> was late in reaching school today. Please ensure that it is not repeated in future',
            'LibraryBook' => 'Your ward <%name%> of class <%class%> has not returned the library book issued to him. Please ensure that he/she returns it on the next working day, positively',
            'Stay Back Information:' => 'Your ward <%name%> of class <%class%> has to stay back after school for <%text_reason%>. Please pick up your ward from school at <%text_time%>. In case your ward has to be allowed to go home on his/her own,please send a proper application signed by you for the same',
            'BUNKING CLASSES' => 'This is to inform you that your ward <%name%> of class <%class%> did not attend the <%text_subject_name%> class in the <%text_period%> period. You are requested to meet the Vice Principal/Educational Supervisor/Class Teacher in this connection on <%text_date%> at <%text_time%>',
            'Worksheets' => "Your ward's worksheets are being sent home for your perusal and appraisal",
            'Well Being' => "We are glad to inform you that your ward has settled well during the current academic year and is doing well. We hope to continue receiving your whole-hearted co-operation for your ward's all round development",
            'Magzine' => 'Entries are invited from all the students for the school magazine. Students should submit their entries to their class teachers/English Teachers/Student Editors',
            'ClassPhoto' => 'Please note that there will be a class photo for classes <%class%> on <%text_date%>',
            'TimeTable' => 'Kindly ensure that your ward <%name%> of class <%class%> brings his/her books according to the time table',
            'Prize Distribution' => 'Congratulations Your ward <%name%> of class <%class%> has won the <%text_prize%> Prize in the <%text_event%> event held on <%text_date%> We wish him/her all the best for the future',
            'Art Competition' => 'An Art competition for classes <%class%> will be held on <%text_date%> Please send all the required material and colours.',
            'File Return' => 'Your ward <%name%> of class <%class%> has taken home his/her art file without permission. Please send it back on the next working day positively.',
            'Art Material Irregular' => 'Your ward <%name%> of class <%class%> has not brought his/her colours and/or art file today.',
            'Competition Preparation' => 'You are requested to meet the Class Teacher <%text_teacher_name%> on <%text_date%> at <%text_time%> to discuss about preparation for upcoming competition/activity',
            'Special Meeting' => 'You are requested to meet the Vice Principal/Educational Supervisor/Class Teacher/Subject teacher/Guidance Counsellor <%text_teacher_name%> on <%text_date%> at <%text_time%> to discuss about the progress of your ward.',
            'Irregular Uniform' => 'It has been observed that in spite of repeated reminders,your ward <%name%> of class <%class%> has not been coming in proper school uniform. Please ensure that he/she does not repeat the same in future',
            'Upcoming Activity' => 'This is to inform you that <%text_activity%> for class <%text_class%> will be held on <%text_date%> Please ensure that your ward brings the required material',
            'Invitation' => 'You are cordially invited for the <%text_event%> on <%text_date%> at <%text_venue%> from <%text_time%> onwards',
            'Inappropriate Behaviour' => 'We regret to inform you that your ward <%name%> of class <%class%> has been showing inappropriate behaviour.You are requested to meet the Vice Principal/Educational Supervisor on <%text_date%> at <%text_time%> to discuss about the same',
            'Damaging School Property' => 'We regret to inform you that your ward <%name%> of class <%class%> has been found damaging school property.You are requested to meet the Vice Principal/Educational Supervisor on <%text_date%> at <%text_time%> to discuss about the same.',
            'Physical Aggeression' => 'We regret to inform you that your ward <%name%> of class <%class%> has been found showing physical aggression.You are requested to meet the Vice Principal/Educational Supervisor on <%text_date%> at <%text_time%> to discuss about the same.',
            'Rule Violation' => 'We regret to inform you that your ward <%name%> of class <%class%> has been found violating school rules.You are requested to meet the Vice Principal/Educational Supervisor on <%text_date%> at <%text_time%> to discuss about the same.',
            'Abusive Language' => 'We regret to inform you that your ward <%name%> of class <%class%> has been found using abusive language.You are requested to meet the Vice Principal/Educational Supervisor on <%text_date%> at <%text_time%> to discuss about the same',
            'Holiday AssignMents' => 'The Holiday assignments have been posted on the school website <%text_school_website_name%>.Please check the same for details',
            'Final Result' => 'This is to inform you that circulars regarding Final Results have been given to the students.Please read the same very carefully and collect the result on the specified date and time.',
            'Report Card Return' => 'The Report card/Result Sheet for <%text_exam_name%> have been given to the students today.Please send it back on the next working day,duly signed',
            'Project Submission' => 'This is to inform you that your ward <%name%> of class <%class%> has not submitted his/her project/assignment in <%text_subject_name%> This may affect his/her grade in the Formative Assessment (FA s).',
            'Prize Winner' => 'Congratulations Your ward <%name%> of class <%class%> has won the <%text_prize%> Prize in the <%text_event%> event held on <%text_date%> We wish him/her all the best for the future',
            'Short Attendance' => 'Your ward <%name%> of class <%class%> has not been regular to school and is short of the required attendance.Please ensure that he/she attends school regularly.',
            'Working Saturday' => 'Please note that <%text_date%> will be a working day for all students/students of classes <%text_class%> The time-table of <%text_day%> will be followed.',
            'Absent For LongTime' => 'It has been observed that your ward <%name%> of class <%class%> has been absent from school since <%text_date%> without any intimation.Kindly notify the school about the reason for the absence with a proper application at the earliest.',
            'Holiday Announcement' => 'This is to inform you that <%text_date%> will be a holiday on account of <%text_reason%>',
            'CALLING STUDENTS ON NON-WORKING DAYS FOR EXTRA CLASSES/ CO-CURRICULAR ACTIVITIES' => 'Your ward <%name%> of class <%class%> has to come to school on <%text_date%> for <%text_reason%> from <%text_start_time%> to <%text_end_time%> In case your ward has to be allowed to go home on his/her own, please send a proper application signed by you for the same.',
            'Fees' => 'Kindly deposit the school fees due by <%text_date%> positively.',
            'Extra Time' => 'Your ward <%name%> of class <%class%> has to stay back after school for <%text_reason%> Please pick up your ward from school at <%text_time%> In case your ward has to be allowed to go home on his/her own,please send a proper application signed by you for the same',
            'Circular' => 'This is to inform you that circular(s)regarding <%text_subject_name%> has/have been given to your ward.Please go through the same.',
            'Picnic' => 'Class <%class%> will be going for a picnic/excursion/outing to <%text_picnic_place%> on <%text_date%> Please pick up your ward from school at <%text_time%> In case your ward has to be allowed to go home on his/her own, please send a proper application signed by you for the same.',
            'Parents, Meeting' => 'The Parent Teacher Meeting for class <%class%> will be held on <%text_date%> from <%text_start_time%> to <%text_end_time%>',
            'Improper Uniform' => 'This is to inform you that your ward <%name%> of class <%class%> is not in proper school uniform today',
            'Fee 1' => 'Kindly deposit the school fees dues as soon as possible.',
            'Fee 2' => 'Kindly deposit the school fees due, else as per school rules, your ward may not be allowed to attend classes.',
            'Diary' => "Kindly fill in the Personal Proforma page in your ward's diary and send it to the Class Teacher positively on the next working day",
            'ExtraClass' => "An extra class for art will be held tomorrow/ on @Model.Date Please send your ward's colours positively.",
            'RequiredMaterial' => 'This is to inform you that <%text_activity%> for class <%class%> will be held on <%text_date%> Please ensure that your ward brings the required material',
            'WorkingDay' => 'Please note that <%text_date%> will be a working day for all students/students of class <%class%> The time-table of <%text_day%> will be followed.',
            'Objectionable Items' => 'We regret to inform you that your ward <%name%> of class <%class%> has been found carrying objectionable items.You are requested to meet the Vice Principal/Educational Supervisor on <%text_date%> at <%text_time%> to discuss about the same',
            'Wishes/Greetings For Festivals' => 'Warm Greetings/Good Wishes to you and your family on the occasion of the <%text_event%>',
            'Formalities For Class XI Stream' => 'Eligibility Criteria for stream of class XI are displayed.Formalities should be completed within <%text_days%> days',
            'NON-SUBMISSION OF ASSIGNMENTS/PROJECTS' => 'This is to inform you that your ward <%name%> of class <%class%> has not submitted his/her project/assignment in <%text_subject_name%>.',
            'Sorry'=>'Dear Parents, Please ignore the previous message sent to you.'
        );

        $schoolId = $arguments[0];
        $templatesData = array();
        foreach ($templates as $key => $value) {
            $row = array();
            $row['body'] = $value;
            $row['name'] = $key;
            $row['schoolId'] = $schoolId;
            $row['created_at'] = 'now';
            $row['updated_at'] = 'now';
            array_push($templatesData, $row);
        }

        $smstemplate = SMSTemplate::where_schoolId($schoolId)->get();
       if (count($smstemplate) == 0 && empty($smstemplate))
            SMSTemplate::insert($templatesData);

    }
}