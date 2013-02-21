/**
 * Created with JetBrains PhpStorm.
 * User: hitanshu
 * Date: 2/20/13
 * Time: 4:29 PM
 * To change this template use File | Settings | File Templates.
 */
//for route user/register
angular.module('app')
    .controller('User_Register', ['$scope', '$http', '$routeParams', '$window', function ($scope, $http, $routeParams, $window) {

    $scope.mobile = '';
    $scope.email = '';
    $scope.password = '';
    $scope.rePassword = '';

    //school Info Variables
    $scope.schoolName = '';
    $scope.contactPerson = '';
    $scope.address = '';
    $scope.schoolContactNumber = '';
    $scope.city = '';
    $scope.state = '';
    $scope.zip = '';
    $scope.senderId;


    //Mobile Verify Screen models
    $scope.mobileVerificationCode = '';
    $scope.SMSResent = false;
    $scope.newMobileNumber = '';
    $scope.IsMobileUpdated = false;
    $scope.SMSResentError = false;
    $scope.IsMobileUpdatedError = false;
    $scope.IsMobileVerified = false;

    //email verify models
    $scope.emailResent = false;
    $scope.newEmail = '';
    $scope.resetEmail = false;

    $scope.register = function () {
        $http.post(
            '/user/post_register',
            {
                "mobile":$scope.mobile,
                "password":$scope.password,
                "email":$scope.email
            }
        ).success(function ($data) {
                $window.location.href = "#/user/register/2";
            }
        ).error(function ($e) {

            });
    }


    $scope.saveSchoolInfo = function () {
        $http.post(
            '/school/update',
            {
                "name":$scope.schoolName,
                "conatct_person":$scope.password,
                "address":$scope.address,
                "city":$scope.city,
                "state":$scope.state,
                "zip":$scope.zip,
                "contact_mobile":$scope.schoolContactNumber,
                "sender_id":$scope.senderId

            }
        ).success(function ($data) {
                console.log($data);
                $window.location.href = "#/user/register/3";
            }
        ).error(function ($e) {

            });
    }

    $scope.verifyMobile = function () {
        $http({
            url:'user/verify_mobile',
            method:"POST",
            data:{mobileActivationCode:$scope.mobileVerificationCode}
        })
            .success(function ($data) {
                if ($data.status == true) {
                    $scope.IsMobileVerified = true;
                    $window.location.href = "#/user/register/4";
                }

                else {
                    $scope.SMSResentError = true;
                }
            }
        ).error(function ($e) {
                console.log($data);
            });
    }

    $scope.sendMobileCodeAgain = function () {
        $http.get(
            '/user/resend_sms'
        ).success(function ($data) {
                if ($data.status = true) {
                    $scope.SMSResent = true;
                }
            }
        ).error(function ($e) {

            });
    }

    $scope.updateMobile = function () {
        $http({
            url:'user/update_mobile',
            method:"POST",
            data:{mobile:$scope.newMobileNumber}
        })
            .success(function ($data) {
                if ($data.status == true) {
                    $scope.IsMobileUpdated = true;
                }
                else {
                    $scope.IsMobileUpdatedError = true;
                    console.log($data);
                }
            }
        ).error(function ($e) {
                console.log($e);
            });
    }


    $scope.sendEmailAgain = function () {
        $http.get(
            '/user/resend_email'
        ).success(function ($data) {
                if ($data.status = true) {
                    $scope.emailResent = true;
                }
            }
        ).error(function ($e) {

            });
    }

    $scope.updateEmail = function () {
        $http({
            url:'user/update_email',
            method:"POST",
            data:{email:$scope.newEmail}
        })
            .success(function ($data) {
                if ($data.status == true) {
                    $scope.resetEmail = true;
                }
                else {
                    console.log($data);
                }
            }
        ).error(function ($e) {
                console.log($e);
            });
    }


}]);