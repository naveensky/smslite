var module = angular.module('app');
module.factory('ReportService', ["$http", "$q", function ($http, $q) {
    return {

        //function to get sms Reports as per various filters. All filters are optional
        getSMS: function (classSections, studentName, teacherName, queueDate, sentDate, pageNumber, pageCount) {

            //create a defer
            var deferred = $q.defer();

            //make post request with params
            $http.post(
                '/report/post_getSMS',
                {
                    "classSections": classSections,
                    "studentName": studentName,
                    "teacherName": teacherName,
                    "fromDate": queueDate,
                    "toDate": sentDate,
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
        },
        getSMSReport: function () {

            //create a defer
            var deferred = $q.defer();

            //make post request with params
            $http.get(
                '/report/get_sms_graph_data',
                { }
            ).success(function (data) {
                    //if data is proper array, return data else empty array
                    deferred.resolve(data);
//                    if (Array.isArray(data))
//                        deferred.resolve(data);
//                    else
//                        deferred.resolve([]);
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

