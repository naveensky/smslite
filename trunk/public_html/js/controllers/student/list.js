'use strict';

//for route student/list
angular.module('app')
    .controller('Student_List', ['$scope', '$http', 'StudentService', 'SchoolService', '$routeParams', function ($scope, $http, studentService, schoolService, routeParams) {
        $scope.classSections = [];
        $scope.morningRoutes = [];
        $scope.eveningRoutes = [];
        $scope.students = [];
        $scope.pageNumber = 1;
        $scope.pageCount = 25;
        $scope.previousPage = 0;
        $scope.nextPage = $scope.pageNumber + 1;
        $scope.mobiles = '';
        $scope.studentData = {};
        $scope.addStudentData = {};
        $scope.showEditScreenError = false;
        $scope.errorEditMessage = '';
        $scope.errorAddMessage = '';
        $scope.deleteStudentSuccess = false;
        $scope.deleteStudentError = false;
        $scope.showAddScreenError = false;


        $scope.getMobileNumbers = function (student) {

            if (student.mobile1 != '')
                $scope.mobiles = student.mobile1;
            if (student.mobile2 != '')
                $scope.mobiles += '\n' + student.mobile2;
            if (student.mobile3 != '')
                $scope.mobiles += '\n' + student.mobile3;
            if (student.mobile4 != '')
                $scope.mobiles += '\n' + student.mobile4;
            if (student.mobile5 != '')
                $scope.mobiles += '\n' + student.mobile5;
            return $scope.mobiles;

        }
        //function to be call for filterStudents
        $scope.filterStudents = function () {
            $scope.pageNumber = 1;
            $scope.pageCount = 25;
            $scope.previousPage = 0;
            $scope.nextPage = $scope.pageNumber + 1;
            studentService.getStudents(
                    $scope.classSections,
                    $scope.morningRoutes,
                    $scope.eveningRoutes,
                    $scope.pageNumber,
                    $scope.pageCount
                ).then(function (students) {
                    $scope.students = students;
                });
        }

        //function to be call when next or previous Button Clicked
        $scope.findNextStudents = function () {
            studentService.getStudents(
                    $scope.classSections,
                    $scope.morningRoutes,
                    $scope.eveningRoutes,
                    $scope.pageNumber,
                    $scope.pageCount
                ).then(function (students) {
                    $scope.students = students;
                });
        }

        schoolService.getClasses().then(function (classes) {
            $scope.classes = classes;
        });

        studentService.getBusRoutes().then(function (routes) {

            $scope.morningroutes = routes.morningRoutes;
            $scope.eveningroutes = routes.eveningRoutes;
        });

        $scope.updateNext = function () {
            $scope.previousPage = $scope.pageNumber;
            $scope.pageNumber = $scope.nextPage;
            $scope.nextPage = $scope.nextPage + 1;
            $scope.findNextStudents();
        }

        $scope.updatePrevious = function () {
            $scope.pageNumber = $scope.previousPage;
            $scope.nextPage = $scope.pageNumber + 1;
            $scope.previousPage = $scope.previousPage - 1;
            $scope.findNextStudents();

        }
        $scope.getFormattedDate = function ($date) {
            if ($date == null)
                return null;
            return moment($date).format('D MMMM  YYYY');
        }

        if (routeParams.code) {
            studentService.getStudentsData(routeParams.code).then(function (data) {
                if (data.length == 1) {
                    $scope.studentData = data[0];
                }
            });
        }

        $scope.deleteStudent = function (index) {
            $scope.deleteStudentSuccess = false;
            $scope.deleteStudentError = false;
            bootbox.confirm("Are you sure you want to delete student", function (result) {
                if (result) {
                    var studentCode = $scope.students[index].code;

                    $http.post(
                        '/student/delete',
                        {
                            "code": studentCode
                        }
                    ).success(function ($data) {
                            if ($data.status) {
                                $scope.students.splice(index, 1);
                                $scope.deleteStudentError = false;
                                $scope.deleteStudentSuccess = true;
                                $scope.deleteSuccessMessage = "Student Deleted Successfully";
                            }
                            else {
                                $scope.deleteStudentSuccess = false;
                                $scope.deleteStudentError = true;
                                $scope.deleteStudentErrorMessage = $data.message;

                            }
                        }
                    ).error(function ($e) {
                            log($e);
                        });
                }

            });

        }

        $scope.exportData = function () {
                        $http.post(
                '/student/exportStudent',
                {
                    classSection: $scope.classSections,
                    morningBusRoute: $scope.morningRoutes,
                    eveningBusRoute: $scope.eveningRoutes
                }
            ).success(function ($data) {
                    if ($data.status != false) {
                        var downloadFrame = '<iframe height="0" width="0" style="display:none" src="' + $data.filePath + '"></iframe>';
                        $(downloadFrame).appendTo('body');
                    }
                    else {
                        alert('No Data to export');
                    }
                }).error(function ($data) {
                    //todo: work for error
                });
        }

        $scope.saveStudentData = function () {
            var code = routeParams.code;
            $scope.studentData.dob = $('#dob').val();
            studentService.updateStudentData($scope.studentData, code).then(function (data) {
                if (data.status) {
                    window.location.href = "#/student/list";
                }
                else {
                    $scope.showEditScreenError = true;
                    $scope.errorEditMessage = data.message;
                }
            });
        }

        $scope.addNewStudent = function () {
            $scope.addStudentData.dob = $('#dateofbirth').val();
            studentService.newStudentAdd($scope.addStudentData).then(function (data) {
                if (data.status) {
                    window.location.href = "#/student/list";
                }
                else {
                    $scope.showAddScreenError = true;
                    $scope.errorAddMessage = data.message;
                }
            });
        }
        //init data for first page load
        $scope.filterStudents();


    }]);