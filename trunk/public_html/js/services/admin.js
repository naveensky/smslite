/**
 * Created with JetBrains PhpStorm.
 * User: hitanshu
 * Date: 24/7/13
 * Time: 2:56 PM
 * To change this template use File | Settings | File Templates.
 */
var module = angular.module('app');
module.factory('AdminService', ["$http", "$q", function ($http, $q) {
    return {

        //function to get sms Reports as per various filters. All filters are optional
        getSMS: function (fromDate, toDate, status, pageNumber, pageCount, selectedSchool) {

            //create a defer
            var deferred = $q.defer();

            //make post request with params
            $http.post(
                '/admin/post_sms_report',
                {
                    "status": status,
                    "fromDate": fromDate,
                    "toDate": toDate,
                    "pageNumber": pageNumber,
                    "pageCount": pageCount,
                    "selectedSchool": selectedSchool
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
        getSchoolsList: function (registrationDate, name, email, schoolPageNumber, schoolPageCount) {

            //create a defer
            var deferred = $q.defer();

            //make post request with params
            $http.post(
                '/admin/post_schools_list',
                {
                    "registrationDate": registrationDate,
                    "name": name,
                    "email": email,
                    "pageNumber": schoolPageNumber,
                    "pageCount": schoolPageCount
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
        getSMSReport: function (fromDate, toDate, status, selectedSchool) {

            //create a defer
            var deferred = $q.defer();

            //make post request with params
            $http.post(
                '/admin/post_pie_chart_data',
                {
                    "fromDate": fromDate,
                    "toDate": toDate,
                    "status": status,
                    "selectedSchool": selectedSchool
                }
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

