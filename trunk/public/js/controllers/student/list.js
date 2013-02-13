/**
 * Created with JetBrains PhpStorm.
 * User: hitanshu
 * Date: 2/13/13
 * Time: 3:16 PM
 * To change this template use File | Settings | File Templates.
 */
'use strict';

//for route user/login
angular.module('app')
    .controller('Student_List', ['$scope', '$http', function ($scope, $http) {
    $scope.classSections = [];
    $scope.morningRoutes = [];
    $scope.eveningRoutes = [];
    $scope.students = [];

    $scope.getStudents = function () {

        $http.post(
            '/student/getStudents',
            {
                "classSection":$scope.classSections,
                "morningBusRoute":$scope.morningRoutes,
                "eveningBusRoute":$scope.eveningRoutes
            }
        ).success(function ($data) {
                $scope.students = $data;
            }
        ).error(function () {
//                $scope.showError = true;
            });
    }
}]);