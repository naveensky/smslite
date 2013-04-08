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
        },
        //search students by mobile or by name
        searchTeachers: function (searchValue) {
            //create a defer
            var deferred = $q.defer();
            //make post request with params
            //make post call for queue the message
            $http.post(
                '/teacher/findTeacherByNameOrMobile',
                {"searchValue": searchValue
                }
            ).success(function (data) {
                    //if data is proper array, return data else empty array
                    if (Array.isArray(data))
                        deferred.resolve(data);
                    else
                        deferred.resolve([]);

                }).error(function (e) {
                    //todo: log this
                    //if there is an error processing data, reject it and log error
                    console.log($e);
                    deferred.reject($e);
                });

            return deferred.promise;
        },
        //get teachers data by sending student code
        getTeachersData: function (teacherCode) {
            var teacherCodes = new Array();
            teacherCodes.push(teacherCode);
            //create a defer
            var deferred = $q.defer();
            //make post request with params
            //make post call for queue the message
            $http.post(
                '/teacher/post_get',
                {"codes": teacherCodes
                }
            ).success(function (data) {
                    //if data is proper array, return data else empty array
                    if (Array.isArray(data))
                        deferred.resolve(data);
                    else
                        deferred.resolve([]);

                }).error(function (e) {
                    //todo: log this
                    //if there is an error processing data, reject it and log error
                    console.log($e);
                    deferred.reject($e);
                });
            return deferred.promise;
        },
        //get teachers data by sending student code
        updateTeacherData: function (teacherData, teacherCode) {
            //make post request with params
            //make post call for queue the message
            var deferred = $q.defer();
            $http.post(
                '/teacher/update',
                {
                    'Name': teacherData.name,
                    'Email': teacherData.email,
                    'Mobile1': teacherData.mobile1,
                    'Mobile2': teacherData.mobile2,
                    'Mobile3': teacherData.mobile3,
                    'Mobile4': teacherData.mobile4,
                    'Mobile5': teacherData.mobile5,
                    'DOB': teacherData.dob,
                    'Department': teacherData.department,
                    'MorningBusRoute': teacherData.morningBusRoute,
                    'EveningBusRoute': teacherData.eveningBusRoute,
                    'Gender': teacherData.gender,
                    'Code': teacherCode

                }
            ).success(function (data) {
                    console.log(data);
                    deferred.resolve(data);
                }).error(function (e) {
                    //todo: log this
                    //if there is an error processing data, reject it and log error
                    console.log($e);
                    deferred.reject($e);
                });
            return deferred.promise;
        }
    }
}]);