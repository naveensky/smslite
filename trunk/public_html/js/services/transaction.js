/**
 * Created with JetBrains PhpStorm.
 * User: hitanshu
 * Date: 13/3/13
 * Time: 4:55 PM
 * To change this template use File | Settings | File Templates.
 */
var module = angular.module('app');
module.factory('TransactionsService', ["$http", "$q", function ($http, $q) {
    return {
        //function to get students as per various filters. All filters are optional
        getTransactions: function () {
            var deferred = $q.defer();          //defer for data

            $http.get(
                    '/school/get_transactions_history'
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
        //function to get students as per various filters. All filters are optional
        getRequestedTemplatesHistory: function () {
            var deferred = $q.defer();          //defer for data
            $http.get(
                    '/school/get_requested_templates_history'
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
        }

    }
}]);

