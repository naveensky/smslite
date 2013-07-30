var module = angular.module('app');
module.factory('SchoolService', ["$http", "$q", function ($http, $q) {
    return {
        //function to get students as per various filters. All filters are optional
        getClasses: function () {
            var deferred = $q.defer();          //defer for data

            $http.get(
                    '/school/get_classes'
                ).success(function (data) {
                    //if data is proper array, return data else empty array
                    if (Array.isArray(data)) {
                        deferred.resolve(data);
                    }
                    else
                        deferred.resolve([]);
                }
            ).error(function ($e) {
                    //if there is an error processing data, reject it and log error
                    log('error', $e);
                    deferred.reject($e);
                }
            );

            return deferred.promise;
        },

        getDepartments: function () {
            var deferred = $q.defer();          //defer for data

            $http.get(
                    '/school/get_departments'
                ).success(function (data) {
                    //if data is proper array, return data else empty array
                    if (Array.isArray(data)) {
                        deferred.resolve(data);
                    }
                    else
                        deferred.resolve([]);
                }
            ).error(function ($e) {
                    //if there is an error processing data, reject it and log error
                    log('error', $e);
                    deferred.reject($e);
                }
            );

            return deferred.promise;
        },

        getMorningBusRoutes: function (ignoreStudents, ignoreTeachers) {

            var deferred = $q.defer();          //defer for data

            $http.get(
                '/school/get_morning_routes',
                { "ignoreStudents": ignoreStudents, "ignoreTeachers": ignoreTeachers  }
            ).success(function (data) {
                    //if data is proper array, return data else empty array
                    if (Array.isArray(data)) {
                        console.log(data);
                        deferred.resolve(data);
                    }
                    else
                        deferred.resolve([]);
                }
            ).error(function ($e) {
                    //if there is an error processing data, reject it and log error
                    log('error', $e);
                    deferred.reject($e);
                }
            );

            return deferred.promise;
        },

        getEveningBusRoutes: function (ignoreStudents, ignoreTeachers) {

            var deferred = $q.defer();          //defer for data

            $http.get(
                '/school/get_evening_routes',
                { "ignoreStudents": ignoreStudents, "ignoreTeachers": ignoreTeachers  }
            ).success(function (data) {
                    //if data is proper array, return data else empty array
                    if (Array.isArray(data)) {
                        deferred.resolve(data);
                    }
                    else
                        deferred.resolve([]);
                }
            ).error(function ($e) {
                    //if there is an error processing data, reject it and log error
                    log('error', $e);
                    deferred.reject($e);
                }
            );
            return deferred.promise;
        },
        getAllSchools: function () {

            var deferred = $q.defer();          //defer for data

            $http.get(
                '/school/get_all_schools',
                { }
            ).success(function (data) {
                    //if data is proper array, return data else empty array
                    if (Array.isArray(data)) {
                        deferred.resolve(data);
                    }
                    else
                        deferred.resolve([]);
                }
            ).error(function ($e) {
                    //if there is an error processing data, reject it and log error
                    log('error', $e);
                    deferred.reject($e);
                }
            );
            return deferred.promise;
        },
        getSchoolAPIData: function () {

            var deferred = $q.defer();          //defer for data

            $http.get(
                '/school/get_api_data',
                { }
            ).success(function (data) {
                    if (data)
                        deferred.resolve(data);
                    else
                        deferred.resolve([]);
                }
            ).error(function ($e) {
                    //if there is an error processing data, reject it and log error
                    log('error', $e);
                    deferred.reject($e);
                }
            );
            return deferred.promise;
        }
    }
}]);

