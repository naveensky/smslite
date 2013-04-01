'use strict';

//for route report/sms
angular.module('app')
    .controller('Report_SMS', ['$scope', '$http', 'ReportService', 'SchoolService', function ($scope, $http, reportService, schoolService) {
        $scope.classSections = [];
        $scope.studentName = '';
        $scope.teacherName = '';
        $scope.smsRows = [];
        $scope.classes = [];
        $scope.pageNumber = 1;
        $scope.pageCount = 25;
        $scope.previousPage = 0;
        $scope.queueDate;
        $scope.sentDate;
        $scope.nextPage = $scope.pageNumber + 1;

        $scope.classes = schoolService.getClasses().then(function (classes) {
            return classes.map(function (classVar) {
                return {"class": classVar, "selected": false};
            })
        });
        reportService.getSMSReport().then(function (data) {
            $scope.smsReportData = data;
            $('#container').highcharts({
                chart: {
                    type: 'column'

                },
                title: {
                    text: 'Last 30 days SMS'
                },
                xAxis: {
                    categories: $scope.smsReportData.dates,
                    title: {
                        text: 'Dates'
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Total SMS'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    dataLabels: {
                        enabled: true
                    },
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [
                    {
                        name: 'Queue SMS',
                        data: $scope.smsReportData.queueValues,
                        color: 'grey'

                    },
                    {
                        name: 'Sent SMS',
                        data: $scope.smsReportData.sentValues,
                        color: 'green'

                    }

                ]
            });
        });

        $scope.filterSMS = function () {
            $scope.pageNumber = 1;
            $scope.pageCount = 25;
            $scope.previousPage = 0;
            $scope.nextPage = $scope.pageNumber + 1;
            $scope.queueDate = $('#dpd1').val();
            $scope.sentDate = $('#dpd2').val();

            if ($scope.queueDate != '' && $scope.sentDate != '') {
                if ($scope.queueDate > $scope.sentDate) {
                    bootbox.alert('Queue Date can not be greater than sentDate');
                    return;
                }
            }

            $scope.smsRows = reportService.getSMS(
                $scope.classSections,
                $scope.studentName,
                $scope.teacherName,
                $scope.queueDate,
                $scope.sentDate,
                $scope.pageNumber,
                $scope.pageCount
            );
        }

        $scope.getNextOrPreviousSMS = function () {
            $scope.queueDate = $('#dpd1').val();
            $scope.sentDate = $('#dpd2').val();

            if ($scope.queueDate != '' && $scope.sentDate != '') {
                if ($scope.queueDate > $scope.sentDate) {
                    bootbox.alert('Queue Date can not be greater than sentDate');
                    return;
                }
            }

            $scope.smsRows = reportService.getSMS(
                $scope.classSections,
                $scope.studentName,
                $scope.teacherName,
                $scope.queueDate,
                $scope.sentDate,
                $scope.pageNumber,
                $scope.pageCount
            );
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

        $scope.getFormattedDate = function ($date) {
            return moment($date).format('D MMM  YYYY h:mm');
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

        $scope.setClassSections = function (value, status) {
            if (status) {
                $scope.classSections.push(value);
            }

            else {
                $scope.classSections.pop(value);
            }

        }

        //init data for first page load
        $scope.filterSMS();

    }]);