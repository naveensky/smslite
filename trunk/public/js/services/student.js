var module = angular.module('app');
module.factory('StudentService', ["$http", "$q", function ($http, $q) {
    return {

        //function to get students as per various filters. All filters are optional
        getStudents: function (classSections, morningRoutes, eveningRoutes, pageNumber, pageCount) {

            //create a defer
            var deferred = $q.defer();

            //make post request with params
            $http.post(
                '/student/getStudents',
                {
                    "classSection": classSections,
                    "morningBusRoute": morningRoutes,
                    "eveningBusRoute": eveningRoutes,
                    "pageNumber": pageNumber,
                    "pageCount": pageCount
                }
            ).success(function (data) {
                    //if data is proper array, return data else empty array
                    if (Array.isArray(data))
                        deferred.resolve(data);
                    else
                        deferred.resolve([]);
                }
            ).error(function ($e) {
                    //todo: log this
                    //if there is an error processing data, reject it and log error
                    console.log($e);
                    deferred.reject($e);
                }
            );

            return deferred.promise;
        }
    }
}]);

