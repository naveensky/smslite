/**
 * Created with JetBrains PhpStorm.
 * User: hitanshu
 * Date: 2/23/13
 * Time: 4:32 PM
 * To change this template use File | Settings | File Templates.
 */
angular.module('app')
    .controller('User_Change_Password', ['$scope', '$http', '$routeParams', '$window', function ($scope, $http, $routeParams, $window) {
    $scope.code = $routeParams.code;
    $scope.x_token = '';
    $scope.errorChangePassword = false;
    $scope.errorChangeMessage = '';
    $scope.rePassword = '';
    $scope.password='';

    $scope.resetPassword = function () {
        $http.post(
            '/user/post_set_password',
            {
                "x_token":$scope.x_token,
                "password":$scope.password
            }
        ).success(function ($data) {
                if ($data.status == true) {
                    $window.location.href = "#/user/login";
                }
                else {
                    $scope.errorChangePassword = true;
                    $scope.errorChangeMessage = $data.message;
                }
            }
        ).error(function ($e) {

            });
    }

    $scope.changePassword = function () {
        $scope.rePassword = "";
    }

}]).directive('sameAs', function () {
        return {
            require:'ngModel',
            link:function (scope, elm, attrs, ctrl) {
                ctrl.$parsers.unshift(function (viewValue) {
                    if (viewValue === scope[attrs.sameAs]) {
                        ctrl.$setValidity('sameAs', true);
                        return viewValue;
                    } else {
                        ctrl.$setValidity('sameAs', false);
                        return undefined;
                    }
                });
            }
        };
    });

