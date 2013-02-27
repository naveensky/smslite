var module = angular.module('app');
module.factory('ReportService', ["$http", "$q", function ($http, $q) {
    return {

        //function to get sms Reports as per various filters. All filters are optional
        getSMS: function (classSections, name, pageNumber, pageCount) {

            //create a defer
            var deferred = $q.defer();

            //make post request with params
            $http.post(
                '/report/post_getSMS',
                {
                    "classSections": classSections,
                    "name": name,
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

