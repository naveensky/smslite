'use strict';

//for route student/list
angular.module('app')
    .controller('Sms_Compose', ['$scope', '$http', 'SmsService', 'SchoolService', function ($scope, $http, smsService, schoolService) {
        $scope.filterType = 'classFilter';
        $scope.message = "";
        $scope.selectedStudents = [];
        $scope.selectedTeachers = [];
        $scope.filterChange = false;
        $scope.queueMessageSuccess = false;
        $scope.finalCreditUsed = '';
        $scope.monitorFunction = function () {
            $scope.previousFilterSelected = $scope.filterType;
        }

        $scope.monitorFunction();
        $scope.creditsAvailable = smsService.getAvailableCredits();

        schoolService.getClasses().then(function (classes) {
            $scope.classes = classes.map(function (value) {
                return {"class": value, "selected": false};
            })
        });

        schoolService.getMorningBusRoutes(false, false).then(function (routes) {
            $scope.morningRoutes = routes.map(function (value) {
                return {"route": value, "selected": false};
            })
        });

        schoolService.getEveningBusRoutes(false, false).then(function (routes) {
            $scope.eveningRoutes = routes.map(function (value) {
                return {"route": value, "selected": false};
            })
        });

        schoolService.getDepartments().then(function (deparments) {
            $scope.departments = deparments.map(function (value) {
                return {"department": value, "selected": false};
            })
        });

        $scope.getSingleMessageCredit = function (message) {
            return Math.ceil($scope.message.length / 160);
        };

        $scope.getCreditsRequired = function () {
            return ($scope.getSingleMessageCredit($scope.message) * $scope.totalSMS());
        };

        $scope.totalSMS = function () {

            var countSMS = 0;
            angular.forEach($scope.selectedStudents, function (selectedStudent) {
                countSMS += selectedStudent.mobileCount;
            })

            angular.forEach($scope.selectedTeachers, function (selectedTeacher) {
                countSMS += selectedTeacher.mobileCount;
            })

            return countSMS;
        }


        $scope.getPeopleCount = function () {
            return $scope.selectedStudents.length + $scope.selectedTeachers.length;
        }

        $scope.countSelectedClasses = function () {
            if (!Array.isArray($scope.classes))
                return 0;

            //count selected classes
            var count = 0;
            for (var i = 0; i < $scope.classes.length; ++i) {
                if ($scope.classes[i].selected)
                    count++;
            }

            return count;
        }

        $scope.countSelectedDepartments = function () {
            if (!Array.isArray($scope.departments))
                return 0;

            //count selected departments
            var count = 0;
            for (var i = 0; i < $scope.departments.length; ++i) {
                if ($scope.departments[i].selected)
                    count++;
            }
            return count;
        }


        $scope.addByClass = function () {
            var selectedClasses = [];

            //get all selected classes
            for (var i = 0; i < $scope.classes.length; ++i) {
                if ($scope.classes[i].selected)
                    selectedClasses.push($scope.classes[i].class);
            }

            //make post call for students
            $http.post(
                '/student/getStudentCodes',
                {"classSection": selectedClasses}
            ).success(function (data) {
                    if (Array.isArray(data)) {
//                        $scope.selectedStudents = $scope.selectedStudents.concat(data);
                        $scope.selectedTeachers = [];
                        $scope.selectedStudents = data;
                        $scope.selectedStudents = $scope.selectedStudents.unique();
                    }

                    //todo: log this as this is not an array
                }).error(function (e) {
                    log('error', e)
                });


        }

        $scope.addByDepartments = function () {
            var selectedDepartments = [];

            //get all selected departments
            for (var i = 0; i < $scope.departments.length; ++i) {
                if ($scope.departments[i].selected)
                    selectedDepartments.push($scope.departments[i].department);
            }

            //make post call for students
            $http.post(
                '/teacher/getTeacherCodes',
                {"departments": selectedDepartments}
            ).success(function (data) {
                    if (Array.isArray(data)) {
                        $scope.selectedStudents = [];
                        $scope.selectedTeachers = data;
                        $scope.selectedTeachers = $scope.selectedTeachers.unique();
                    }
                    //todo: log if not array
                }).error(function (e) {
                    log('error', e)
                });
        }


        $scope.addByBusRoutes = function () {
            var selectedMorningBusRoutes = [];
            var selectedEveningBusRoutes = [];

            //get all selected eveningRoutes
            for (var i = 0; i < $scope.eveningRoutes.length; ++i) {
                if ($scope.eveningRoutes[i].selected)
                    selectedEveningBusRoutes.push($scope.eveningRoutes[i].route);
            }
            //get all selected morningRoutes
            for (var i = 0; i < $scope.morningRoutes.length; ++i) {
                if ($scope.morningRoutes[i].selected)
                    selectedMorningBusRoutes.push($scope.morningRoutes[i].route);
            }


            //make post call for studentcodes from morningBusRoutes
            if (selectedMorningBusRoutes.length != 0) {
                $http.post(
                    '/student/getStudentCodes',
                    {"morningBusRoute": selectedMorningBusRoutes}

                ).success(function (data) {
                        if (Array.isArray(data)) {
                            $scope.selectedStudents = data;
                            $scope.selectedStudents = $scope.selectedStudents.unique();
                        }
                        console.log(Array.isArray(data));

                        //todo: log this as this is not an array
                    }).error(function (e) {
                        log('error', e)
                    });

            }
            //make post call for studentcodes from morningBusRoutes
            if (selectedEveningBusRoutes.length != 0) {
                $http.post(
                    '/student/getStudentCodes',
                    {"eveningBusRoute": selectedEveningBusRoutes}

                ).success(function (data) {
                        if (Array.isArray(data)) {
                            $scope.selectedStudents = data;
                            $scope.selectedStudents = $scope.selectedStudents.unique();
                        }
                        console.log(Array.isArray(data));

                        //todo: log this as this is not an array
                    }).error(function (e) {
                        log('error', e)
                    });
            }
            if (selectedMorningBusRoutes.length != 0) {
                //make post call for teacherCodes from morning bus routes
                $http.post(
                    '/teacher/getTeacherCodes',
                    {"morningBusRoute": selectedMorningBusRoutes
                    }
                ).success(function (data) {
                        if (Array.isArray(data)) {
                            $scope.selectedTeachers = data;
                            $scope.selectedTeachers = $scope.selectedTeachers.unique();
                        }
                        console.log(Array.isArray(data));

                        //todo: log this as this is not an array
                    }).error(function (e) {
                        log('error', e)
                    });
            }
            if (selectedEveningBusRoutes.length != 0) {
                //make post call for teacherCodes from morning bus routes
                $http.post(
                    '/teacher/getTeacherCodes',
                    {"eveningBusRoute": selectedEveningBusRoutes
                    }
                ).success(function (data) {
                        if (Array.isArray(data)) {
                            $scope.selectedTeachers = data;
                            $scope.selectedTeachers = $scope.selectedTeachers.unique();
                        }
                        console.log(Array.isArray(data));

                        //todo: log this as this is not an array
                    }).error(function (e) {
                        log('error', e)
                    });
            }

        }

        $scope.changeFilterSelection = function (selection) {
            var confirmation = confirm("Are you sure you want to change the filter?");
            if (confirmation) {
                $scope.filterType = selection;
                $scope.monitorFunction();
                $scope.selectedStudents = [];
                $scope.selectedTeachers = [];
            }
            else {
                $scope.filterType = $scope.previousFilterSelected;
            }

        }
        $scope.selectedPeople = [
            {"name": "Naveen Gupta", "mobiles": ["9891410701", "9810140705"]},
            {"name": "Hitanshu Malhotra", "mobiles": ["9891410701", "9810140705"]},
            {"name": "Akhil Gupta", "mobiles": ["9891410701", "9810140705"]},
            {"name": "Keshav Ashta", "mobiles": ["9891410701", "9810140705"]},
            {"name": "Raman Mittal", "mobiles": ["9891410701", "9810140705"]}
        ];

        $scope.updatePreviewList = function (studentCodes, teacherCodes) {


        }

        $scope.checkBeforeSend = function () {

            if ($scope.message == "")
                return true;
            if ($scope.message.length > 320 || $scope.getCreditsRequired() > $scope.creditsAvailable || $scope.totalSMS() <= 0 || $scope.message.length == 0 || $scope.queueMessageSuccess == true)
                return true;

            return false;
        }

        $scope.queueSMS = function () {

            //make post call for queue the message
            $http.post(
                '/sms/post_create',
                {"studentCodes": $scope.selectedStudents,
                    "teacherCodes": $scope.selectedTeachers,
                    "message": $scope.message
                }
            ).success(function (data) {
                    if (data.status = true) {
                        $scope.queueMessageSuccess = true;
                        if (data.result.numberOfCreditsUsed)
                            $scope.finalCreditUsed = data.result.numberOfCreditsUsed;
                        if (data.result.numberOfCreditsUsedTeachers)
                            $scope.finalCreditUsed += data.result.numberOfCreditsUsedTeachers;
                    }
                    console.log(data);
//                    console.log(Array.isArray(data));
                    //todo: log this as this is not an array
                }).error(function (e) {
                    log('error', e)
                });
        }
    }
    ])
;