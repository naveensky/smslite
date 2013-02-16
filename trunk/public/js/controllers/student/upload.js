'use strict';

//for route student/list
angular.module('app')
    .controller('Student_Upload', ['$scope', '$http', function ($scope, $http) {
        $scope.files = [];
        $scope.fileUploaded = function (data) {
            $scope.files = data.files;
            console.log(data.files);
        }
    }]);