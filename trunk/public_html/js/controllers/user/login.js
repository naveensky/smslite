'use strict';

//for route user/login
angular.module('app')
    .controller('User_Login', ['$scope', '$http', '$window', function ($scope, $http, $window) {
        $scope.showError = false;
        $scope.login = function (user) {
            $scope.showError = false;
//            var csrf = $('input[name="csrf_token"]').val();
            $http.post(
                '/user/post_login',
                {
                    "email": user.email,
                    "password": user.password
//                    "csrf_token":csrf
                }).
                success(function (data) {
                    if (data.status) {
                        $window.location.href = data.url;
                    }
                }
            ).error(function (data) {
                    $scope.showError = true;
                    log('error', data);
                });
        }
    }]);