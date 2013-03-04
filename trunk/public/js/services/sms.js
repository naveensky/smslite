var module = angular.module('app');
module.factory('SmsService', ["$http", "$q", function ($http, $q) {
    return {
        //function to get students as per various filters. All filters are optional
        sendSms: function () {
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

        getAvailableCredits: function () {
            var deferred = $q.defer();          //defer for data

            //todo: think about updating it every 10 secs
            $http.get(
                    '/school/get_available_credits'
                ).success(function (data) {
                    deferred.resolve(data);
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

