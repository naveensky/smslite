/**
 * Created with JetBrains PhpStorm.
 * User: hitanshu
 * Date: 2/20/13
 * Time: 4:29 PM
 * To change this template use File | Settings | File Templates.
 */
//for route user/register
angular.module('app')
    .controller('User_Register', ['$scope', '$routeParams', '$http', '$window', function ($scope, $http, $routeParams, $window) {

    $scope.registerUser = function () {
        //register user
        $window.location.href = "#/user/register/2";
    }

    $scope.saveSchoolInfo = function () {
        $window.location.href = "#/user/register/3";
    }

    $scope.verifyMobile = function () {
        $window.location.href = "#/user/register/4";
    }

}]);