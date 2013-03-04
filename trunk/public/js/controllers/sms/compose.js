'use strict';

//for route student/list
angular.module('app')
    .controller('Sms_Compose', ['$scope', '$http', 'SmsService', 'SchoolService', function ($scope, $http, smsService, schoolService) {
        $scope.filterType = 'classFilter';
        $scope.message = "";
        $scope.creditsAvailable = smsService.getAvailableCredits();

        $scope.classes = schoolService.getClasses().then(function (classes) {
            return classes.map(function (value) {
                return {"class": value, "selected": false};
            })
        });

        $scope.morningRoutes = schoolService.getMorningBusRoutes(false, false).then(function (routes) {
            return routes.map(function (value) {
                return {"route": value, "selected": false};
            })
        });

        $scope.eveningRoutes = schoolService.getEveningBusRoutes(false, false).then(function (routes) {
            return routes.map(function (value) {
                return {"route": value, "selected": false};
            })
        });

        $scope.departments = schoolService.getDepartments().then(function (deparments) {
            return deparments.map(function (value) {
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

        $scope.addByClass = function(){

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
    }]);