/**
 * Created with JetBrains PhpStorm.
 * User: hitanshu
 * Date: 2/22/13
 * Time: 6:31 PM
 * To change this template use File | Settings | File Templates.
 */

angular.module('app')
    .controller('User_Forgot_Password', ['$scope', '$http', '$window', function ($scope, $http, $window) {

    //models related send by email forgot password screen
    $scope.email = '';
    $scope.errorEmail = false;
    $scope.successEmail = false;
    $scope.emailSuccessMessage = '';
    $scope.emailErrorMessage = '';


    //models used for forgot password send by mobile
    $scope.emailId='';
    $scope.mobileNumber='';
    $scope.successMobile=false;//if successful then show the success message
    $scope.errorMobile=false;//if unsuccessful show error message
    $scope.successMobileMessage='';
    $scope.errorMobileMessage='';


    $scope.sendByEmail = function () {
        $http.post(
            '/user/post_forgot_password',
            {
                "email":$scope.email
            }
        ).success(function ($data) {
                if ($data.status == true) {
                    $scope.errorEmail=false;
                    $scope.successEmail = true;
                    $scope.emailSuccessMessage = $data.message;
                }
                else {
                    $scope.successEmail=false;
                    $scope.errorEmail = true;
                    $scope.emailErrorMessage = $data.message;
                }
            }
        ).error(function ($e) {
            });
    }



    $scope.sendByMobile = function () {
        $http.post(
            '/user/send_password_mobile',
            {
                "email":$scope.emailId,
                "mobile":$scope.mobileNumber
            }
        ).success(function ($data) {
                if ($data.status == true) {
                    $scope.errorMobile=false;
                    $scope.successMobile = true;
                    $scope.successMobileMessage = $data.message;
                }
                else {
                    $scope.successMobile=false;
                    $scope.errorMobile = true;
                    $scope.errorMobileMessage = $data.message;
                }
            }
        ).error(function ($e) {

            });
    }

}]);