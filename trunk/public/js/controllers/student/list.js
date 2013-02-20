'use strict';

//for route student/list
angular.module('app')
    .controller('Student_List', ['$scope', '$http', 'StudentService', function ($scope, $http, studentService) {
        $scope.classSections = [];
        $scope.morningRoutes = [];
        $scope.eveningRoutes = [];
        $scope.students = [];
        $scope.pageNumber = 1;
        $scope.pageCount = 25;
        $scope.previousPage = 0;
        $scope.nextPage = $scope.pageNumber + 1;

        $scope.filterStudents = function () {
            $scope.students = studentService.getStudents(
                $scope.classSections,
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
            $scope.filterStudents();
        }

        $scope.updatePrevious = function () {
            $scope.pageNumber = $scope.previousPage;
            $scope.nextPage = $scope.pageNumber + 1;
            $scope.previousPage = $scope.previousPage - 1;
            $scope.filterStudents();

        }
        //init data for first page load
        $scope.filterStudents();

    }]);