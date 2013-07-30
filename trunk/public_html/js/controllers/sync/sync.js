/**
 * Created with JetBrains PhpStorm.
 * User: hitanshu
 * Date: 5/7/13
 * Time: 3:06 PM
 * To change this template use File | Settings | File Templates.
 */
/**
 * Created with JetBrains PhpStorm.
 * User: hitanshu
 * Date: 21/3/13
 * Time: 7:20 PM
 * To change this template use File | Settings | File Templates.
 */
'use strict';

//for route admin
angular.module('app')
    .controller('Sync_Controller', ['$scope', '$http', 'SchoolService', function ($scope, $http, schoolService) {

        $scope.APIKey = '';
        $scope.studentAPIUrl = '';
        $scope.teacherAPIUrl = '';
        $scope.fetchStudents = true;
        $scope.fetchTeachers = true;
        $scope.showSync = false;
        $scope.fetchValidationError = false;
        $scope.fetchValidationErrorMessage = '';
        $scope.studentsImported = 0;
        $scope.studentsUpdated = 0;
        $scope.studentImportKeys = '';
        $scope.studentImportStatusBlock = false;
        $scope.teachersImported = 0;
        $scope.teachersUpdated = 0;
        $scope.teacherImportKeys = '';
        $scope.teacherImportStatusBlock = false;
        $scope.disableSaveAndSync = false;
        $scope.studentUrlError = false;
        $scope.teacherUrlError = false;
        $scope.studentUrlErrorMessage = '';
        $scope.teacherUrlErrorMessage = '';
        $scope.serverError = false;
        $scope.serverErrorMessage = '';


        schoolService.getSchoolAPIData().then(function (data) {
            $scope.APIKey = data.apiKey;
            $scope.studentAPIUrl = data.studentAPIUrl;
            $scope.teacherAPIUrl = data.teacherAPIUrl;
            console.log(data);
        });

        $scope.getUpdatedStudents = function () {
            return $scope.studentsUpdated;
        };

        $scope.getImportedStudents = function () {
            return $scope.studentsImported;
        };

        $scope.getUpdatedTeachers = function () {
            return $scope.teachersUpdated;
        };

        $scope.getImportedTeachers = function () {
            return $scope.teachersImported;
        };


        $scope.removeError = function () {
            if ($scope.fetchStudents || $scope.fetchTeachers) {
                $scope.fetchValidationError = false;
                $scope.fetchValidationErrorMessage = '';
                $scope.disableSaveAndSync = false;
            }
            else {
                $scope.fetchValidationError = true;
                $scope.fetchValidationErrorMessage = 'Please choose you want to fetch students, teachers or both';
                $scope.disableSaveAndSync = true;
            }
        }

        $scope.saveAndSync = function () {
            $scope.fetchValidationError = false;
            $scope.fetchValidationErrorMessage = '';
            $scope.studentsImported = 0;
            $scope.studentsUpdated = 0;
            $scope.studentImportKeys = '';
            $scope.studentImportStatusBlock = false;
            $scope.teachersImported = 0;
            $scope.teachersUpdated = 0;
            $scope.teacherImportKeys = '';
            $scope.teacherImportStatusBlock = false;
            $scope.disableSaveAndSync = false;
            $scope.studentUrlError = false;
            $scope.teacherUrlError = false;
            $scope.studentUrlErrorMessage = '';
            $scope.teacherUrlErrorMessage = '';
            $scope.serverError = false;
            $scope.serverErrorMessage = '';
            if ($scope.fetchStudents || $scope.fetchTeachers) {
                $scope.showSync = true;
                $http.post(
                    '/sync/post_save',
                    {
                        "APIKey": $scope.APIKey,
                        "studentAPIUrl": $scope.studentAPIUrl,
                        "teacherAPIUrl": $scope.teacherAPIUrl,
                        "fetchStudents": $scope.fetchStudents,
                        "fetchTeachers": $scope.fetchTeachers,
                        "sendToSchool": $scope.notifySchool
                    }
                ).success(function ($data) {
                        if ($data.validationError) {
                            $scope.fetchValidationError = true;
                            $scope.fetchValidationErrorMessage = 'Please choose you want to fetch students, teachers or both';
                        }
                        else {
                            if ($data.student.fetchStudent == true || $data.student.fetchStudent == 'true') {
                                if ($data.student.urlError == true) {
                                    $scope.studentUrlError = true;
                                    $scope.studentUrlErrorMessage = 'Please Check API Url for students';
                                    $scope.showSync = false;
                                }
                                else {
                                    var studentStatus = $data.student.syncStatus;
                                    $scope.studentsImported = studentStatus.studentsImported;
                                    $scope.studentsUpdated = studentStatus.studentsUpdated;
                                    $scope.studentImportKeys = studentStatus.importKeys;
                                    $scope.showSync = false;
                                    $scope.studentImportStatusBlock = true;

                                }
                            }

                            if ($data.teacher.fetchTeacher == true || $data.teacher.fetchTeacher == 'true') {
                                if ($data.teacher.urlError == true) {
                                    $scope.teacherUrlError = true;
                                    $scope.teacherUrlErrorMessage = 'Please Check API Url teachers';
                                    $scope.showSync = false;
                                }
                                else {
                                    var teacherStatus = $data.teacher.syncStatus;
                                    $scope.teachersImported = teacherStatus.teachersImported;
                                    $scope.teachersUpdated = teacherStatus.teachersUpdated;
                                    $scope.teacherImportKeys = teacherStatus.importKeys;
                                    $scope.showSync = false;
                                    $scope.teacherImportStatusBlock = true;
                                }
                            }

                        }
                    }
                ).error(function ($e) {
                        //todo: log error
                        $scope.serverError = true;
                        $scope.serverErrorMessage = $e;
                        $scope.showSync = false;
                    }
                );
            }
            else {
                $scope.fetchValidationError = true;
                $scope.fetchValidationErrorMessage = 'Please choose you want to fetch students, teachers or both';
                $scope.disableSaveAndSync = false;

            }

        }
    }]);