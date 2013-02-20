var module = angular.module('app');
module.factory('StudentService', ["$http", "$q", function ($http, $q) {
    return {

        //function to get students as per various filters. All filters are optional
        getStudents: function (classSections, morningRoutes, eveningRoutes, pageNumber, pageCount) {
            var deferred = $q.defer();
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
                    if (Array.isArray(data))
                        deferred.resolve(data);
                    else
                        deferred.resolve([]);
                }
            ).error(function ($e) {
                    //todo: log this
                    console.log($e);
                    deferred.reject($e);
                }
            );

            return deferred.promise;
        }
    }
}]);

