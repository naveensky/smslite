/**
 * Created with JetBrains PhpStorm.
 * User: hitanshu
 * Date: 21/3/13
 * Time: 7:20 PM
 * To change this template use File | Settings | File Templates.
 */
'use strict';

//for route admin
angular.module('app')
    .controller('Admin_Controller', ['$scope', '$http', 'SchoolService', 'AdminService', function ($scope, $http, schoolService, adminService) {

        $scope.notifySchool = true;
        $scope.remarks = '';
        $scope.schoolSelected = 0;
        $scope.selectedSchool = '';
        $scope.fromDate = '';
        $scope.toDate = '';
        $scope.status = '';
        $scope.allocateCredits = '';
        $scope.pageNumber = 1;
        $scope.pageCount = 25;
        $scope.previousPage = 0;
        $scope.nextPage = $scope.pageNumber + 1;

        $scope.schoolPageNumber = 1;
        $scope.schoolPageCount = 25;
        $scope.schoolPreviousPage = 0;
        $scope.schoolNextPage = $scope.schoolPageNumber + 1;
        $scope.name = '';
        $scope.email = '';
        $scope.registrationDate = '';

        $scope.amount = '';
        $scope.discount = '';
        $scope.showError = false;
        $scope.showSuccess = false;
        $scope.message = '';
        $scope.smsRows = [];
        $scope.schoolList = [];

        schoolService.getAllSchools().then(function (schools) {
            $scope.schools = schools;
        });

        $scope.getGrossAmount = function () {

            return ($scope.amount - ($scope.discount / 100 * $scope.amount));
        }

        adminService.getSchoolsList(
                $scope.registrationDate, $scope.name, $scope.email, $scope.schoolPageNumber, $scope.schoolPageCount
            ).then(function (list) {
                $scope.schoolList = list;

            });

        $scope.smsRows = adminService.getSMS($scope.fromDate, $scope.toDate, $scope.status, $scope.pageNumber, $scope.pageCount, $scope.selectedSchool);


        $scope.creditsAllocate = function () {

            if ($scope.amount <= 0) {
                bootbox.alert('Amount must be Greater than 0');
                return;
            }

            if ($scope.schoolSelected == 0) {
                bootbox.alert('Please Select the school');
                return;
            }

            $http.post(
                '/admin/post_allocate_credits',
                {
                    "school": $scope.schoolSelected,
                    "amount": $scope.amount,
                    "discount": $scope.discount,
                    "credits": $scope.allocateCredits,
                    "remarks": $scope.remarks,
                    "sendToSchool": $scope.notifySchool
                }
            ).success(function ($data) {
                    if ($data.status) {
                        $scope.showError = false;
                        $scope.showSuccess = true;
                        $scope.message = $data.message;
                    }
                    else {
                        $scope.showSuccess = false;
                        $scope.showError = true;
                        $scope.message = $data.message;
                    }
                }
            ).error(function ($e) {
                    //todo: log error
                }
            );
        }

        $scope.filterSMS = function () {
            $scope.pageNumber = 1;
            $scope.pageCount = 25;
            $scope.previousPage = 0;
            $scope.nextPage = $scope.pageNumber + 1;
            $scope.fromDate = $('#dpd1').val();
            $scope.toDate = $('#dpd2').val();

            if ($scope.fromDate != '' && $scope.toDate != '') {
                if ($scope.fromDate > $scope.toDate) {
                    bootbox.alert('From Date can not be greater than To Date');
                    return;
                }
            }

            $scope.smsRows = adminService.getSMS(
                $scope.fromDate, $scope.toDate, $scope.status, $scope.pageNumber, $scope.pageCount, $scope.selectedSchool
            );

            adminService.getSMSReport($scope.fromDate, $scope.toDate, $scope.status, $scope.selectedSchool).then(function (data) {
                $scope.smsReportData = data;
                console.log($scope.smsReportData);
                $('#container').highcharts({
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false
                    },
                    title: {
                        text: 'Last 30 Days SMS Report'
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br/>Count:<b>({point.y})</b>'
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                color: '#000000',
                                connectorColor: '#000000',
                                format: '<b>{point.name} ({point.y})</b>: {point.percentage:.1f} %'
                            }
                        }
                    },

                    series: [
                        {
                            type: 'pie',
                            name: 'Percentage',
                            data: $scope.smsReportData
                        }
                    ]
                });
            });
        }

        $scope.filterSchool = function () {
            $scope.schoolPageNumber = 1;
            $scope.schoolPageCount = 25;
            $scope.schoolPreviousPage = 0;
            $scope.schoolNextPage = $scope.schoolPageNumber + 1;
            $scope.registrationDate = $('#registeredDate').val();
            adminService.getSchoolsList(
                    $scope.registrationDate, $scope.name, $scope.email, $scope.schoolPageNumber, $scope.schoolPageCount
                ).then(function (list) {
                    $scope.schoolList = list;
                });
        }

        $scope.getStatusCss = function ($smsRow) {
            switch ($smsRow.status) {
                case 'pending':
                    return '';
                    break;
                case 'sent':
                    return 'success';
                    break;
                case 'fail':
                    return 'error';
                    break;
                default:
                    break;
            }
        }

        $scope.updateNext = function () {
            $scope.previousPage = $scope.pageNumber;
            $scope.pageNumber = $scope.nextPage;
            $scope.nextPage = $scope.nextPage + 1;
            $scope.getNextOrPreviousSMS();
        }

        $scope.updatePrevious = function () {
            $scope.pageNumber = $scope.previousPage;
            $scope.nextPage = $scope.pageNumber + 1;
            $scope.previousPage = $scope.previousPage - 1;
            $scope.getNextOrPreviousSMS();

        }

        $scope.getNextOrPreviousSMS = function () {
            $scope.fromDate = $('#dpd1').val();
            $scope.toDate = $('#dpd2').val();

            if ($scope.fromDate != '' && $scope.toDate != '') {
                if ($scope.fromDate > $scope.toDate) {
                    bootbox.alert('From Date can not be greater than To Date');
                    return;
                }
            }

            $scope.smsRows = adminService.getSMS(
                $scope.fromDate, $scope.toDate, $scope.status, $scope.pageNumber, $scope.pageCount, $scope.selectedSchool
            );
        }


        $scope.getNextOrPreviousSchool = function () {
            $scope.registrationDate = $('#registeredDate').val();

            adminService.getSchoolsList(
                    $scope.registrationDate, $scope.name, $scope.email, $scope.schoolPageNumber, $scope.schoolPageCount
                ).then(function (list) {
                    $scope.schoolList = list;
                    console.log($scope.schoolList);
                });
        }

        $scope.getFormattedDate = function ($date, $isDate) {
            if ($isDate)
                return moment($date).format('D MMM  YYYY');

            return moment($date).format('D MMM  YYYY h:mm');
        }


        $scope.updateNextSchool = function () {
            $scope.schoolPreviousPage = $scope.schoolPageNumber;
            $scope.schoolPageNumber = $scope.schoolNextPage;
            $scope.schoolNextPage = $scope.schoolNextPage + 1;
            $scope.getNextOrPreviousSchool();
        }

        $scope.updatePreviousSchool = function () {
            $scope.schoolPageNumber = $scope.schoolPreviousPage;
            $scope.schoolNextPage = $scope.schoolPageNumber + 1;
            $scope.schoolPreviousPage = $scope.schoolPreviousPage - 1;
            $scope.getNextOrPreviousSchool();

        }


        adminService.getSMSReport($scope.fromDate, $scope.toDate, $scope.status, $scope.selectedSchool).then(function (data) {
            $scope.smsReportData = data;
            console.log($scope.smsReportData);
            $('#container').highcharts({
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false
                },
                title: {
                    text: 'Last 30 Days SMS Report'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br/>Count:<b>({point.y})</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            color: '#000000',
                            connectorColor: '#000000',
                            format: '<b>{point.name} ({point.y})</b>:{point.percentage:.1f}%'
                        }
                    }
                },
                series: [
                    {
                        type: 'pie',
                        name: 'Percentage',
                        data: $scope.smsReportData
                    }
                ]
            });
        });

    }]);