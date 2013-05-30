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
        },
        //search students by mobile or by name
        searchStudents: function (searchValue) {
            //create a defer
            var deferred = $q.defer();
            //make post request with params
            //make post call for queue the message
            $http.post(
                '/student/findStudentByNameOrMobileOrAdmissionNumber',
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
        //get students data by sending student code
        getStudentsData: function (studentCode) {
            var studentCodes = new Array();
            studentCodes.push(studentCode);
            //create a defer
            var deferred = $q.defer();
            //make post request with params
            //make post call for queue the message
            $http.post(
                '/student/post_get',
                {"codes": studentCodes
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
        //get students data by sending student code
        updateStudentData: function (studentData, studentCode) {
            //make post request with params
            //make post call for queue the message
            var deferred = $q.defer();
            $http.post(
                '/student/update',
                {
                    'Name': studentData.name,
                    'Email': studentData.email,
                    'MothersName': studentData.motherName,
                    'FathersName': studentData.fatherName,
                    'Mobile1': studentData.mobile1,
                    'Mobile2': studentData.mobile2,
                    'Mobile3': studentData.mobile3,
                    'Mobile4': studentData.mobile4,
                    'Mobile5': studentData.mobile5,
                    'DOB': studentData.dob,
                    'admissionNumber': studentData.uniqueIdentifier,
                    'ClassStandard': studentData.classStandard,
                    'ClassSection': studentData.classSection,
                    'MorningBusRoute': studentData.morningBusRoute,
                    'EveningBusRoute': studentData.eveningBusRoute,
                    'Gender': studentData.gender,
                    'Code': studentCode

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
        },
        //insert new student
        newStudentAdd: function (addStudentData) {
            //make post request with params
            //make post call for queue the message
            var deferred = $q.defer();
            $http.post(
                '/student/create',
                {
                    'Name': addStudentData.name,
                    'Email': addStudentData.email,
                    'MothersName': addStudentData.motherName,
                    'FathersName': addStudentData.fatherName,
                    'Mobile1': addStudentData.mobile1,
                    'Mobile2': addStudentData.mobile2,
                    'Mobile3': addStudentData.mobile3,
                    'Mobile4': addStudentData.mobile4,
                    'Mobile5': addStudentData.mobile5,
                    'DOB': addStudentData.dob,
                    'admission': addStudentData.uniqueIdentifier,
                    'ClassStandard': addStudentData.classStandard,
                    'ClassSection': addStudentData.classSection,
                    'MorningBusRoute': addStudentData.morningBusRoute,
                    'EveningBusRoute': addStudentData.eveningBusRoute,
                    'gender': addStudentData.gender
                }
            ).success(function (data) {
                    deferred.resolve(data);
                }).error(function (e) {
                    //todo: log this
                    //if there is an error processing data, reject it and log error
                    console.log($e);
                    deferred.reject($e);
                });
            return deferred.promise;
        },
        getBusRoutes: function () {

            var deferred = $q.defer();          //defer for data

            $http.get(
                    '/student/get_bus_routes'
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

