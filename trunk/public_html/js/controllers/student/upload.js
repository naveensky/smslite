'use strict';

//for route student/list
angular.module('app')
    .controller('Student_Upload', ['$scope', '$http', function ($scope, $http) {
        $scope.files = [];
        $scope.path = '';
        $scope.fileName;
        $scope.numberOfStudents = 0;
        $scope.rowErrors = 0;
        $scope.errorMessage = '';
        $scope.showError = false;
        $scope.showSuccess = false;
        $scope.importStatus = false;
        $scope.fileUploaded = function (data) {

            if (data.result.status == "success") {
                $scope.showError = false;
                var files = [data.result];
                $scope.files = files;
                $scope.path = files[0].path;
                $scope.showSuccess = true;
            }
            else {
                $scope.files = [];
                $scope.showSuccess = false;
                $scope.showError = true;
                $scope.fileName = data.result.fileName;
                $scope.errorMessage = data.result.message;
            }
            console.log($scope.files);
        }
        $scope.importStudents = function () {
            $http.post(
                '/student/post_upload',
                {
                    "filePath": $scope.path
                }
            ).success(function ($data) {
                    $scope.showSuccess = false;
                    $scope.showError = false;
                    $scope.files = [];
                    $scope.importStatus = true;
                    $scope.numberOfStudents = $data.numberOfStudentInserted;
                    $scope.rowErrors = $data.rowNumbersError;
                }
            ).error(function ($e) {
                    //todo: log error
                    console.log($e);
                }
            );
        }

        $scope.resetModel = function () {
            $scope.files = [];
            $scope.numberOfStudents = 0;
            $scope.rowErrors = 0;
            $scope.errorMessage = '';
            $scope.showError = false;
            $scope.showSuccess = false;
            $scope.importStatus = false;
            $scope.path = '';
            $scope.fileName = '';
        }
    }]);