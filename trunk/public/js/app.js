'use strict';

// Declare app level module which depends on filters, and services
angular.module('app', []).
    config(['$routeProvider', function ($routeProvider) {
        $routeProvider.when('/user/login', {templateUrl: 'user/login', controller: 'User_Login'});


        ///student routes
        $routeProvider
            .when('/student', {templateUrl: '/student', controller: 'Student_List'})
            .when('/student/upload', {templateUrl: '/student/upload', controller: 'Student_Upload'})
            .when('/student/list',{templateUrl: '/student/list',controller:'Student_List'});


        $routeProvider.otherwise({redirectTo: '/'});
    }]);