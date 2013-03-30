/**
 * Created with JetBrains PhpStorm.
 * User: hitanshu
 * Date: 12/3/13
 * Time: 7:28 PM
 * To change this template use File | Settings | File Templates.
 */
/**
 * Created with JetBrains PhpStorm.
 * User: hitanshu
 * Date: 2/22/13
 * Time: 6:31 PM
 * To change this template use File | Settings | File Templates.
 */

angular.module('app')
    .controller('User_Account', ['$scope', '$http', 'TransactionsService', function ($scope, $http, transactionsService) {

        $scope.profile = {};
        $scope.schoolUpdateError = false;
        $scope.schoolUpdateSuccess = false;
        $scope.rePassword = '';
        $scope.newPassword = '';
        $scope.oldPassword = '';
        $scope.message = '';
        $scope.successUpdatePassword = false;
        $scope.errorUpdatePassword = false;
        $scope.transactionsHistory = transactionsService.getTransactions();

        $scope.getFormattedDate = function ($date) {
            return moment($date).format('Do MMMM  YYYY');
        }

        $scope.getUserProfile = function () {
            $http.get(
                    'user/get_user_profile'
                ).success(function ($data) {
                    $scope.profile = $data;
                    console.log($scope.profile);
                }).error(function ($e) {

                });
        }
        $scope.getUserProfile();

        $scope.updateProfile = function () {
            $http.post(
                '/school/post_update',
                {
                    "name": $scope.profile.name,
                    "contact_person": $scope.profile.contactPerson,
                    "address": $scope.profile.address,
                    "city": $scope.profile.city,
                    "state": $scope.profile.state,
                    "zip": $scope.profile.zip,
                    "contact_mobile": $scope.profile.contactMobile
//                    "sender_id":$scope.senderId

                }
            ).success(function ($data) {
                    if ($data.status == true) {
                        $scope.schoolUpdateError = false;
                        $scope.schoolUpdateSuccess = true;
                    }
                    else {
                        $scope.schoolUpdateSuccess = false;
                        $scope.schoolUpdateError = true;

                    }
                }
            ).error(function ($e) {

                });
        }

        $scope.changePassword = function () {
            $scope.rePassword = "";
        }

        $scope.updatePassword = function () {
            $http.post(
                '/user/post_update_password',
                {
                    "oldPassword": $scope.oldPassword,
                    "newPassword": $scope.newPassword
                }
            ).success(function ($data) {
                    if ($data.status == true) {
                        $scope.errorUpdatePassword = false;
                        $scope.successUpdatePassword = true;
                        $scope.message = $data.message;
                    }
                    else {
                        $scope.successUpdatePassword = false;
                        $scope.errorUpdatePassword = true;
                        $scope.message = $data.message;
                    }
                }
            ).error(function ($e) {

                });
        }


    }]).directive('sameAs', function () {
        return {
            require: 'ngModel',
            link: function (scope, elm, attrs, ctrl) {
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