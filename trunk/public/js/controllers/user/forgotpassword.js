/**
 * Created with JetBrains PhpStorm.
 * User: hitanshu
 * Date: 2/22/13
 * Time: 6:31 PM
 * To change this template use File | Settings | File Templates.
 */

angular.module('app')
    .controller('User_Forgot_Password', ['$scope', '$http', '$window', function ($scope, $http, $window) {

    $scope.email = '';
    $scope.errorEmail = false;
    $scope.successEmail = false;
    $scope.emailSuccessMessage = '';
    $scope.emailErrorMessage = '';

    $scope.sendByEmail = function () {
        $http.post(
            '/user/post_forgot_password',
            {
                "email":$scope.email
            }
        ).success(function ($data) {
                if ($data.status == true) {
                    $scope.successEmail = true;
                    $scope.emailSuccessMessage = $data.message;
                }
                else {
                    $scope.errorEmail = true;
                    $scope.emailErrorMessage = $data.message;
                }
            }
        ).error(function ($e) {

            });
    }

}]);