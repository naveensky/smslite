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

        schoolService.getAllSchools().then(function (schools) {
            $scope.schools = schools;
        });

    }]);