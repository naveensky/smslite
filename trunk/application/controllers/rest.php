<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 28/5/13
 * Time: 3:09 PM
 * To change this template use File | Settings | File Templates.
 */

class Rest_Controller extends Base_Controller
{
    public function action_getStudents()
    {
        $student1 = array("admissionNo" => 12456, "name" => 'Raman', 'email' => 'raman@gmail.com',
            'fatherName' => '', 'motherName' => '', 'mobile1' => '9999999999', 'mobile2' => '9999999999',
            'mobile3' => '9999999999', 'mobile4' => '9999999999', 'mobile5' => '', 'dob' => '', 'classStandard' => '6',
            'classSection' => 'A', 'morningBusRoute' => '400', 'eveningBusRoute' => '500', "gender" => "male");
        $student2 = array("admissionNo" => 12457, "name" => 'Keshav', 'email' => 'keshav@gmail.com',
            'fatherName' => '', 'motherName' => '', 'mobile1' => '9999999999', 'mobile2' => '9999999999',
            'mobile3' => '9999999999', 'mobile4' => '9999999999', 'mobile5' => '', 'dob' => '', 'classStandard' => '6',
            'classSection' => 'A', 'morningBusRoute' => '400', 'eveningBusRoute' => '500', "gender" => "male");
        $student = array($student1, $student2);
        return '{ "AcademicYear" : null,
    "AcademicYear_Id" : 0,
    "Activities" : [  ],
    "ActivityGrades" : [  ],
    "ActivityResults" : [  ],
    "Attendance" : null,
    "BoardRegistrationNo" : null,
    "CBSERollNo" : null,
    "Class" : { "Activities" : [  ],
        "Attendances" : [  ],
        "ClassLabel" : { "AssessmentSchemas" : [  ],
            "Classes" : [  ],
            "CreatedDate" : "0001-01-01T00:00:00",
            "DeletedDate" : null,
            "Id" : 0,
            "ModifiedDate" : null,
            "Name" : null,
            "NumericCode" : 6,
            "ShortName" : null
          },
        "ClassLabel_Id" : 0,
        "ClassTeacher" : null,
        "CreatedDate" : "0001-01-01T00:00:00",
        "DeletedDate" : null,
        "Exams" : [  ],
        "Id" : 0,
        "Label" : null,
        "ModifiedDate" : null,
        "Orientation" : null,
        "SectionLabel" : { "Classes" : [  ],
            "CreatedDate" : "0001-01-01T00:00:00",
            "DeletedDate" : null,
            "Id" : 0,
            "ModifiedDate" : null,
            "Name" : "A",
            "ShortName" : "A"
          },
        "SectionLabel_Id" : 0,
        "Students" : [  ],
        "Subjects" : [  ],
        "TeacherClassSubjectMaps" : [  ],
        "Term1Path" : null,
        "Term2Path" : null
      },
    "Class_Id" : 0,
    "CreatedDate" : "0001-01-01T00:00:00",
    "DeletedDate" : null,
    "ExamResults" : [  ],
    "ExamSectionResults" : null,
    "HealthInformations" : null,
    "House" : null,
    "Id" : 0,
    "IgnoreActivities" : [  ],
    "IgnoreSubjects" : [  ],
    "IsPromoted" : false,
    "ModifiedDate" : null,
    "RollNo" : null,
    "SelfAwarenesses" : null,
    "SmsReports" : [  ],
    "StudentAssignments" : [  ],
    "StudentAttendances" : null,
    "StudentMaster" : { "AdmissionNo" : "00302",
        "BloodGroup" : "A+",
        "Category" : null,
        "City" : null,
        "Country" : null,
        "CreatedDate" : "0001-01-01T00:00:00",
        "DOB" : "2001-08-30T00:00:00",
        "DeletedDate" : null,
        "EWS" : null,
        "EmailId" : null,
        "FamilyIncome" : null,
        "FatherName" : "SATISH YADAV",
        "FirstName" : "ABHIMANYU",
        "FullName" : "ABHIMANYU YADAV",
        "Gender" : true,
        "Id" : 0,
        "ImagePath" : null,
        "LastName" : "YADAV",
        "Minority" : null,
        "MobileNo" : "9211916804",
        "ModifiedDate" : null,
        "MotherName" : null,
        "Nationality" : null,
        "PhoneNo" : "9911925274",
        "PinCode" : null,
        "RegistrationId" : null,
        "State" : null,
        "StreetAddress" : null,
        "Students" : null,
        "Transport" : null,
        "UserId" : null,
        "UserName" : null
      },
    "StudentMaster_Id" : 0,
    "StudentScores" : [  ],
    "Subjects" : [  ]
  }';
    }
}