'use strict';

//for route student/list
angular.module('app')
    .controller('Sms_Compose', ['$scope', '$http', 'SmsService', 'SchoolService', function ($scope, $http, smsService, schoolService) {
        $scope.filterType = 'classFilter';
        $scope.message = "";
        $scope.creditsAvailable = smsService.getAvailableCredits();

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
            return $scope.selectedPeople.length;
        }


        $scope.selectedPeople = [
            {"name": "Naveen Gupta", "mobiles": ["9891410701", "9810140705"]},
            {"name": "Hitanshu Malhotra", "mobiles": ["9891410701", "9810140705"]},
            {"name": "Akhil Gupta", "mobiles": ["9891410701", "9810140705"]},
            {"name": "Keshav Ashta", "mobiles": ["9891410701", "9810140705"]},
            {"name": "Raman Mittal", "mobiles": ["9891410701", "9810140705"]}
        ];


    }]);