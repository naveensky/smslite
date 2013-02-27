'use strict';

//for route report/sms
angular.module('app')
    .controller('Report_SMS', ['$scope', '$http', 'ReportService', function ($scope, $http, reportService) {
        $scope.classSections = [];
        $scope.name = '';
        $scope.smsRows = [];
        $scope.pageNumber = 1;
        $scope.pageCount = 25;
        $scope.previousPage = 0;
        $scope.nextPage = $scope.pageNumber + 1;

        $scope.filterSMS = function () {
            $scope.smsRows = reportService.getSMS(
                $scope.classSections,
                $scope.name,
                $scope.pageNumber,
                $scope.pageCount
            );
        }

        $scope.updateNext = function () {
            $scope.previousPage = $scope.pageNumber;
            $scope.pageNumber = $scope.nextPage;
            $scope.nextPage = $scope.nextPage + 1;
            $scope.filterSMS();
        }

        $scope.updatePrevious = function () {
            $scope.pageNumber = $scope.previousPage;
            $scope.nextPage = $scope.pageNumber + 1;
            $scope.previousPage = $scope.previousPage - 1;
            $scope.filterSMS();

        }

        $scope.getStatusCss = function ($smsRow) {
            switch ($smsRow.status) {
                case 'pending':
                    return '';
                    break;
                case 'sent':
                    return 'success';
                    break;
                case 'failed':
                    return 'error';
                    break;
                default:
                    break;
            }
        }

        $scope.setClassSections = function ($class) {
            $scope.classSections.push($class);
        }

        //init data for first page load
        $scope.filterSMS();


    }]);