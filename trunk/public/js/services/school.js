var module = angular.module('app');
module.factory('SchoolService', ["$http", "$q", function ($http, $q) {
    return {

        //function to get students as per various filters. All filters are optional
        getClasses: function () {

            //create a defer
            var deferred = $q.defer();

            //make post request with params
            $http.get(
                    '/school/get_classes'
                ).success(function (data) {
                    //if data is proper array, return data else empty array
                    if (Array.isArray(data)){
                        deferred.resolve(data);
                    }
                    else
                        deferred.resolve([]);
                }
            ).error(function ($e) {
                    //todo: log this
                    //if there is an error processing data, reject it and log error
                    log('error', $e);
                    deferred.reject($e);
                }
            );

            return deferred.promise;
        }
    }
}]);

