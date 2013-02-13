'use strict';

//for route user/login
angular.module('app')
    .controller('User_Login', ['$scope', '$http', function ($scope, $http) {
        $scope.showError = false;

        $scope.login = function (user) {
            $scope.showError = false;
            $http.post(
                '/user/post_login',
                {
                    "email": user.email,
                    "password": user.password
                },
                function ($data) {
                    //todo: redirect to dashboard
                    console.log($data);
                }
            ).error(function () {
                    $scope.showError = true;
                });
            console.log(user);
        }
    }]);