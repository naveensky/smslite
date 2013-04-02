'use strict';

//for route student/list
angular.module('app')
    .controller('Student_List', ['$scope', '$http', 'StudentService', 'SchoolService', function ($scope, $http, studentService, schoolService) {
        $scope.classSections = [];
        $scope.morningRoutes = [];
        $scope.eveningRoutes = [];
        $scope.students = [];
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
        //function to be call for filterStudents
        $scope.filterStudents = function () {
            $scope.pageNumber = 1;
            $scope.pageCount = 25;
            $scope.previousPage = 0;
            $scope.nextPage = $scope.pageNumber + 1;
            $scope.students = studentService.getStudents(
                $scope.classSections,
                $scope.morningRoutes,
                $scope.eveningRoutes,
                $scope.pageNumber,
                $scope.pageCount
            );
        }
        //function to be call when next or previous Button Clicked
        $scope.findNextStudents = function () {
            $scope.students = studentService.getStudents(
                $scope.classSections,
                $scope.morningRoutes,
                $scope.eveningRoutes,
                $scope.pageNumber,
                $scope.pageCount
            );
        }

        schoolService.getClasses().then(function (classes) {
            $scope.classes = classes;
        });

        schoolService.getMorningBusRoutes(false, false).then(function (routes) {
            $scope.morningroutes = routes;
        });

        schoolService.getEveningBusRoutes(false, false).then(function (routes) {
            $scope.eveningroutes = routes;
        });

        $scope.updateNext = function () {
            $scope.previousPage = $scope.pageNumber;
            $scope.pageNumber = $scope.nextPage;
            $scope.nextPage = $scope.nextPage + 1;
            $scope.findNextStudents();
        }

        $scope.updatePrevious = function () {
            $scope.pageNumber = $scope.previousPage;
            $scope.nextPage = $scope.pageNumber + 1;
            $scope.previousPage = $scope.previousPage - 1;
            $scope.findNextStudents();

        }
        //init data for first page load
        $scope.filterStudents();

    }]);