'use strict';

//for route user/login
angular.module('app')
    .controller('Teacher_List', ['$scope', '$http', function ($scope, $http) {
    $scope.departments = [];
    $scope.morningRoutes = [];
    $scope.eveningRoutes = [];
    $scope.teachers = [];
    $scope.pageNumber = 1;
    $scope.pageCount = 25;
    $scope.previousPage = 0;
    $scope.nextPage = $scope.pageNumber + 1;

    $scope.getTeachers = function () {
        $http.post(
            '/teacher/getTeachers',
            {
                "departments":$scope.departments,
                "morningBusRoutes":$scope.morningRoutes,
                "eveningBusRoutes":$scope.eveningRoutes,
                "pageNumber":$scope.pageNumber,
                "pageCount":$scope.pageCount
            }
        ).success(function ($data) {
                if (Array.isArray($data))
                    $scope.teachers = $data;
            }
        ).error(function ($e) {
                alert($e);
            });
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