var module = angular.module('app');
module.factory('TeacherService', ["$http", "$q", function ($http, $q) {
    return {
        //function to get teachers as per various filters. All filters are optional
        getTeachers: function (departments, morningRoutes, eveningRoutes, pageNumber, pageCount) {

            //create a defer
            var deferred = $q.defer();

            //make post request with params
            $http.post(
                '/teacher/getTeachers',
                {
                    "departments": departments,
                    "morningBusRoutes": morningRoutes,
                    "eveningBusRoutes": eveningRoutes,
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