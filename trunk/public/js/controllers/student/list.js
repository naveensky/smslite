'use strict';

//for route student/list
angular.module('app')
    .controller('Student_List', ['$scope', '$http', function ($scope, $http) {
    $scope.classSections = [];
    $scope.morningRoutes = [];
    $scope.eveningRoutes = [];
    $scope.students = [];
    $scope.pageNumber = 1;
    $scope.pageCount = 2;
    $scope.previousPage = 0;
    $scope.nextPage = $scope.pageNumber + 1;

    $scope.getStudents = function () {
        $http.post(
            '/student/getStudents',
            {
                "classSection":$scope.classSections,
                "morningBusRoute":$scope.morningRoutes,
                "eveningBusRoute":$scope.eveningRoutes,
                "pageNumber":$scope.pageNumber,
                "pageCount":$scope.pageCount
            }
        ).success(function ($data) {
                if (Array.isArray($data))
                    $scope.students = $data;
            }
        ).error(function ($e) {
                alert($e);
            });
    }


    $scope.updateNext = function () {
        $scope.previousPage = $scope.pageNumber;
        $scope.pageNumber = $scope.nextPage;
        $scope.nextPage = $scope.nextPage + 1;
        $scope.getStudents();
    }

    $scope.updatePrevious = function () {
        $scope.pageNumber = $scope.previousPage;
        $scope.nextPage = $scope.pageNumber + 1;
        $scope.previousPage = $scope.previousPage - 1;
        $scope.getStudents();

    }
    //init data for first page load
    $scope.getStudents();

}]);