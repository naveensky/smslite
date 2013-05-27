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
        $scope.templateBody = '';
        $scope.templateName = '';
        $scope.successUpdatePassword = false;
        $scope.errorUpdatePassword = false;
        $scope.successRequestTemplate = false;
        $scope.errorRequestTemplate = false;
        $scope.requestTemplateMessage = '';
        $scope.iAgree = true;
        $scope.transactionsHistory = transactionsService.getTransactions();
        transactionsService.getRequestedTemplatesHistory().then(function (result) {
            $scope.requestedTemplatesHistory = result;
        });

        $scope.getFormattedDate = function ($date) {
            return moment($date).format('Do MMMM  YYYY');
        }

        $scope.getStatusCss = function ($Row) {
            switch ($Row.status) {
                case 'pending':
                    return '';
                    break;
                case 'approved':
                    return 'success';
                    break;
                case 'fail':
                    return 'error';
                    break;
                default:
                    break;
            }
        }

        $scope.getTemplateStatusMessage = function (status) {
            switch (status) {
                case 'pending':
                    return 'Pending';
                    break;
                case 'approved':
                    return 'Approved';
                    break;
                case 'fail':
                    return 'Not Approved';
                    break;
                default:
                    break;
            }
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

        $scope.getSingleMessageCredit = function () {
            if ($scope.message == null)
                return 0;
            return Math.ceil($scope.message.length / 160);
        };

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

        $scope.requestTemplate = function () {
            $http.post(
                '/user/post_request_new_template',
                {
                    "templateName": $scope.templateName,
                    "templateBody": $scope.templateBody
                }
            ).success(function (data) {
                    if (data.status == true) {
                        $scope.errorRequestTemplate = false;
                        $scope.successRequestTemplate = true;
                        $scope.requestTemplateMessage = data.message;
                        window.location.href = "#/user/requested_templates_history";
                    }
                    else {
                        $scope.successRequestTemplate = false;
                        $scope.errorRequestTemplate = true;
                        $scope.requestTemplateMessage = data.message;
                    }
                }).error(function ($e) {

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