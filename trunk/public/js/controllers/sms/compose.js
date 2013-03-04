'use strict';

//for route student/list
angular.module('app')
    .controller('Sms_Compose', ['$scope', '$http', 'SmsService', 'SchoolService', function ($scope, $http, smsService, schoolService) {
        $scope.filterType = 'classFilter';
        $scope.message = "";
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
            return 5;
            //todo:pending
        };

        $scope.totalSMS = function () {
            return 10;
            //todo: pending
        };

        $scope.getPeopleCount = function () {
            return $scope.selectedStudents.length + $scope.selectedTeachers.length;
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
                        $scope.selectedStudents.push(data.code);
                        $scope.selectedStudents = $scope.selectedStudents.unique();
                    }

                    console.log(Array.isArray(data));

                    //todo: log this as this is not an array
                }).error(function (e) {
                    log('error', e)
                });
        }

        $scope.addByDepartments = function () {
            var selectedDepartments = [];

            //get all selected classes
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
                        $scope.selectedTeachers.push(data);
                        $scope.selectedTeachers = $scope.selectedTeachers.unique();
                    }

                    console.log(Array.isArray(data));

                    //todo: log this as this is not an array
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
            $http.post(
                '/student/getStudentCodes',
                {"morningBusRoute": selectedMorningBusRoutes}

            ).success(function (data) {
                    if (Array.isArray(data)) {
                        $scope.selectedStudents.push(data);
                        $scope.selectedStudents = $scope.selectedStudents.unique();
                    }
                    console.log(Array.isArray(data));

                    //todo: log this as this is not an array
                }).error(function (e) {
                    log('error', e)
                });

            //make post call for studentcodes from morningBusRoutes
            $http.post(
                '/student/getStudentCodes',
                {"eveningBusRoute": selectedEveningBusRoutes}

            ).success(function (data) {
                    if (Array.isArray(data)) {
                        $scope.selectedStudents.push(data);
                        $scope.selectedStudents = $scope.selectedStudents.unique();
                    }
                    console.log(Array.isArray(data));

                    //todo: log this as this is not an array
                }).error(function (e) {
                    log('error', e)
                });


            //make post call for teacherCodes from morning bus routes
            $http.post(
                '/teacher/getTeacherCodes',
                {"morningBusRoute": selectedMorningBusRoutes
                    }
            ).success(function (data) {
                    if (Array.isArray(data)) {
                        $scope.selectedTeachers.push(data);
                        $scope.selectedTeachers = $scope.selectedTeachers.unique();
                    }
                    console.log(Array.isArray(data));

                    //todo: log this as this is not an array
                }).error(function (e) {
                    log('error', e)
                });


        //make post call for teacherCodes from morning bus routes
        $http.post(
            '/teacher/getTeacherCodes',
            {"EveningBusRoute": selectedEveningBusRoutes
            }
        ).success(function (data) {
                if (Array.isArray(data)) {
                    $scope.selectedTeachers.push(data);
                    $scope.selectedTeachers = $scope.selectedTeachers.unique();
                }
                console.log(Array.isArray(data));

                //todo: log this as this is not an array
            }).error(function (e) {
                log('error', e)
            });
    }

        $scope.selectedStudents = [];

        $scope.selectedTeachers = [];

        $scope.selectedPeople = [
            {"name": "Naveen Gupta", "mobiles": ["9891410701", "9810140705"]},
            {"name": "Hitanshu Malhotra", "mobiles": ["9891410701", "9810140705"]},
            {"name": "Akhil Gupta", "mobiles": ["9891410701", "9810140705"]},
            {"name": "Keshav Ashta", "mobiles": ["9891410701", "9810140705"]},
            {"name": "Raman Mittal", "mobiles": ["9891410701", "9810140705"]}
        ];

        $scope.updatePreviewList = function (studentCodes, teacherCodes) {


        }

        $scope.get
    }
    ])
;