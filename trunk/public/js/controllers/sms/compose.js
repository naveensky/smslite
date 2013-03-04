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
                        $scope.selectedStudents.push(data);
                        $scope.selectedStudents = $scope.selectedStudents.unique();
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
    }
    ])
;