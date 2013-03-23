/**
 * Created with JetBrains PhpStorm.
 * User: hitanshu
 * Date: 21/3/13
 * Time: 7:20 PM
 * To change this template use File | Settings | File Templates.
 */
'use strict';

//for route admin
angular.module('app')
    .controller('Admin_Controller', ['$scope', '$http', 'SchoolService', function ($scope, $http, schoolService) {

        $scope.notifySchool = true;
        $scope.remarks = '';
        $scope.schoolSelected = 0;
        $scope.allocateCredits = '';
        $scope.amount = '';
        $scope.discount = '';
        $scope.showError = false;
        $scope.showSuccess = false;
        $scope.message = '';

        $scope.getGrossAmount = function () {

            return ($scope.amount - ($scope.discount / 100 * $scope.amount));
        }

        schoolService.getAllSchools().then(function (schools) {
            $scope.schools = schools;
        });

        $scope.creditsAllocate = function () {
            $http.post(
                '/admin/post_allocate_credits',
                {
                    "school": $scope.schoolSelected,
                    "amount": $scope.amount,
                    "discount": $scope.discount,
                    "credits": $scope.allocateCredits,
                    "remarks": $scope.remarks,
                    "sendToSchool": $scope.notifySchool
                }
            ).success(function ($data) {
                    if ($data.status) {
                        $scope.showError = false;
                        $scope.showSuccess = true;
                        $scope.message = $data.message;
                    }
                    else {
                        $scope.showSuccess = false;
                        $scope.showError = true;
                        $scope.message = $data.message;
                    }
                }
            ).error(function ($e) {
                    //todo: log error
                }
            );
        }
    }]);