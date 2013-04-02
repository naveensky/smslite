'use strict';

//for route user/login
angular.module('app')
    .controller('Teacher_List', ['$scope', '$http', 'TeacherService','SchoolService', function ($scope, $http, teacherService,schoolService) {
        $scope.departments = [];
        $scope.morningRoutes = [];
        $scope.eveningRoutes = [];
        $scope.teachers = [];
        $scope.pageNumber = 1;
        $scope.pageCount = 25;
        $scope.previousPage = 0;
        $scope.nextPage = $scope.pageNumber + 1;
        $scope.mobiles = '';

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
        schoolService.getDepartments().then(function (deparments) {
            $scope.totaldepartments = deparments;
        });

        schoolService.getMorningBusRoutes(false, false).then(function (routes) {
            $scope.morningroutes = routes;
        });

        schoolService.getEveningBusRoutes(false, false).then(function (routes) {
            $scope.eveningroutes = routes;
        });

        $scope.getTeachers = function () {
            $scope.teachers = teacherService.getTeachers(
                $scope.departments,
                $scope.morningRoutes,
                $scope.eveningRoutes,
                $scope.pageNumber,
                $scope.pageCount
            );
        }

        $scope.findNextTeachers = function () {
            $scope.teachers = teacherService.getTeachers(
                $scope.departments,
                $scope.morningRoutes,
                $scope.eveningRoutes,
                $scope.pageNumber,
                $scope.pageCount
            );
        }

        $scope.updateNext = function () {
            $scope.previousPage = $scope.pageNumber;
            $scope.pageNumber = $scope.nextPage;
            $scope.nextPage = $scope.nextPage + 1;
            $scope.findNextTeachers();
        }

        $scope.updatePrevious = function () {
            $scope.pageNumber = $scope.previousPage;
            $scope.nextPage = $scope.pageNumber + 1;
            $scope.previousPage = $scope.previousPage - 1;
            $scope.findNextTeachers();

        }

        //init data for first page load
        $scope.getTeachers();

    }]);