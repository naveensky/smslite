'use strict';

//for route user/login
angular.module('app')
    .controller('Teacher_List', ['$scope', '$http', 'TeacherService', function ($scope, $http, teacherService) {
        $scope.departments = [];
        $scope.morningRoutes = [];
        $scope.eveningRoutes = [];
        $scope.teachers = [];
        $scope.pageNumber = 1;
        $scope.pageCount = 25;
        $scope.previousPage = 0;
        $scope.nextPage = $scope.pageNumber + 1;

        $scope.getTeachers = function () {
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
            $scope.getTeachers();
        }

        $scope.updatePrevious = function () {
            $scope.pageNumber = $scope.previousPage;
            $scope.nextPage = $scope.pageNumber + 1;
            $scope.previousPage = $scope.previousPage - 1;
            $scope.getTeachers();

        }

        //init data for first page load
        $scope.getTeachers();

    }]);