'use strict';

//service for loader module
//the service sets loading bar whenever any ajax request is fired.
angular.module('LoaderServices', [])
    .config(function ($httpProvider) {
        $httpProvider.responseInterceptors.push('appHttpInterceptor');
        var spinnerFunction = function (data, headersGetter) {
            $('#ajax-loader').show();
            return data;
        };
        $httpProvider.defaults.transformRequest.push(spinnerFunction);
    })
    // register the interceptor as a service, intercepts ALL angular ajax http calls
    .factory('appHttpInterceptor', function ($q, $window) {
        return function (promise) {
            return promise.then(function (response) {
                $('#ajax-loader').hide();
                return response;
            }, function (response) {
                $('#ajax-loader').hide();
                return $q.reject(response);
            });
        };
    });

var login = angular.module('myLoginCheck', []).
    factory('logincheck', function ($http) {
        return function () {
            $http.get(
                    '/user/check_login'
                ).success(function (data) {
                    return data.status;
                }
            ).error(function ($e) {
                    //if there is an error processing data, reject it and log error
                    log('error', $e);
                }
            );
        };
    });