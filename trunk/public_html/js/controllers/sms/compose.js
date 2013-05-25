'use strict';

//for route student/list
angular.module('app')
    .controller('Sms_Compose', ['$scope', '$http', 'SmsService', 'SchoolService', 'StudentService', 'TeacherService', function ($scope, $http, smsService, schoolService, studentService, teacherService) {
        $scope.filterType = 'classFilter';
        $scope.message;
        $scope.selectedStudents = [];
        $scope.selectedTeachers = [];
        $scope.searchResults = [];
        $scope.filterChange = false;
        $scope.queueMessageSuccess = false;
        $scope.finalCreditUsed = '';
        $scope.errorSMS = false;
        $scope.errorMessage = '';
        $scope.pagedItems = [];
        $scope.itemsPerPage = 10;
        $scope.currentPage = 0;
        $scope.templates = [];
        $scope.templateSelected = 1;
        $scope.showPlaceholder = false;
        $scope.model = {};
        $scope.sendCopy = false;
        $scope.studentList = false;
        $scope.teacherList = false;
        $scope.studentAndTeacherList = false;

        //client side pagination code used for added user list view
        $scope.calculatePageItemsForUserAdded = function (isStudent, isTeacher) {
            $scope.pagedStudents = [];
            $scope.pagedTeachers = [];
            $scope.currentStudentListPage = 0;
            $scope.currentTeacherListPage = 0;
            if (isStudent == true && isTeacher == false) {
                for (var i = 0; i < $scope.selectedStudents.length; i++) {
                    if (i % $scope.itemsPerPage === 0) {
                        $scope.pagedStudents[Math.floor(i / $scope.itemsPerPage)] = [ $scope.selectedStudents[i] ];
                    } else {
                        $scope.pagedStudents[Math.floor(i / $scope.itemsPerPage)].push($scope.selectedStudents[i]);
                    }
                }
            }

            else if (isStudent == false && isTeacher == true) {
                for (var j = 0; j < $scope.selectedTeachers.length; j++) {
                    if (j % $scope.itemsPerPage === 0) {
                        $scope.pagedTeachers[Math.floor(j / $scope.itemsPerPage)] = [ $scope.selectedTeachers[j] ];
                    } else {
                        $scope.pagedTeachers[Math.floor(j / $scope.itemsPerPage)].push($scope.selectedTeachers[j]);
                    }
                }

            }

            else {
                for (var k = 0; k < $scope.selectedStudents.length; k++) {
                    if (k % $scope.itemsPerPage === 0) {
                        $scope.pagedStudents[Math.floor(k / $scope.itemsPerPage)] = [ $scope.selectedStudents[k] ];
                    } else {
                        $scope.pagedStudents[Math.floor(k / $scope.itemsPerPage)].push($scope.selectedStudents[k]);
                    }
                }

                for (var l = 0; l < $scope.selectedTeachers.length; l++) {
                    if (l % $scope.itemsPerPage === 0) {
                        $scope.pagedTeachers[Math.floor(l / $scope.itemsPerPage)] = [ $scope.selectedTeachers[l] ];
                    } else {
                        $scope.pagedTeachers[Math.floor(l / $scope.itemsPerPage)].push($scope.selectedTeachers[l]);
                    }
                }

            }

        }

        $scope.prevPageStudent = function () {
            if ($scope.currentStudentListPage > 0) {
                $scope.currentStudentListPage--;
            }
        };

        $scope.nextPageStudent = function () {
            if ($scope.currentStudentListPage < $scope.pagedStudents.length - 1) {
                $scope.currentStudentListPage++;
            }
        };

        $scope.prevPageTeacher = function () {
            if ($scope.currentTeacherListPage > 0) {
                $scope.currentTeacherListPage--;
            }
        };

        $scope.nextPageTeacher = function () {
            if ($scope.currentTeacherListPage < $scope.pagedTeachers.length - 1) {
                $scope.currentTeacherListPage++;
            }
        };

        //monitors the previous value of the filter
        $scope.monitorFunction = function () {
            $scope.previousFilterSelected = $scope.filterType;
        }

        $scope.monitorFunction();

        $scope.checkTemplateSelected = function () {
            return true;
        }

        $scope.calculatePageItems = function () {
            for (var i = 0; i < $scope.searchResults.length; i++) {
                if (i % $scope.itemsPerPage === 0) {
                    $scope.pagedItems[Math.floor(i / $scope.itemsPerPage)] = [ $scope.searchResults[i] ];
                } else {
                    $scope.pagedItems[Math.floor(i / $scope.itemsPerPage)].push($scope.searchResults[i]);
                }
            }
        }

        $scope.getStatusCss = function (people) {
            if (people.searchPeople.selected) {
                return 'success';
            }
            return '';
        }

        $scope.prevPage = function () {
            if ($scope.currentPage > 0) {
                $scope.currentPage--;
            }
        };

        $scope.nextPage = function () {
            if ($scope.currentPage < $scope.pagedItems.length - 1) {
                $scope.currentPage++;
            }
        };


        smsService.getAvailableCredits().then(function (result) {
            $scope.creditsAvailable = result;
        });

        smsService.getAvailableTemplates().then(function (result) {
            $scope.templates = result;
            $scope.templateSelected = $scope.templates[0].id;
            $scope.message = $scope.templates[0].body;
            $scope.link = '/sms/get_template_message_vars/' + $scope.templateSelected;
        });

        $scope.templateMessage = function () {
            $scope.message = null;
            $scope.link = null;
            angular.forEach($scope.templates, function (template) {
                if (template.id == $scope.templateSelected) {
                    console.log(template.body);
                    $scope.message = template.body;
                    $scope.link = '/sms/get_template_message_vars/' + $scope.templateSelected;

                }
            })
        }

        $scope.searchPeople = function () {

            //clear previous results before making new search hit
            $scope.searchResults = [];
            $scope.currentPage = 0;
            $scope.pagedItems = [];
            $scope.selectedStudents = [];
            $scope.selectedTeachers = [];

            studentService.searchStudents($scope.searchValue).then(function (students) {

                //map the results as per view requirement
                var result = students.map(function (value) {
                    return {"searchPeople": value, "selected": false, "isTeacher": false};
                });

                //push the results to container array
                $scope.searchResults = $scope.searchResults.concat(result);
                $scope.calculatePageItems();
                console.log($scope.searchResults.length);

            });

            teacherService.searchTeachers($scope.searchValue).then(function (teachers) {
                var result = teachers.map(function (value) {
                    return {"searchPeople": value, "selected": false, "isTeacher": true};
                });

                $scope.searchResults = $scope.searchResults.concat(result);
                $scope.calculatePageItems();
                console.log($scope.searchResults.length);

            });
        }
        schoolService.getClasses().then(function (classes) {
            $scope.classes = classes.map(function (value) {
                return {"class": value, "selected": false};
            })
        });

        schoolService.getMorningBusRoutes(false, false).then(function (routes) {
            $scope.morningRoutes = routes.map(function (value) {
                return {"route": value, "selected": false};
            })
        });

        schoolService.getEveningBusRoutes(false, false).then(function (routes) {
            $scope.eveningRoutes = routes.map(function (value) {
                return {"route": value, "selected": false};
            })
        });

        schoolService.getDepartments().then(function (deparments) {
            $scope.departments = deparments.map(function (value) {
                return {"department": value, "selected": false};
            })
        });

        $scope.getSingleMessageCredit = function () {
            if ($scope.message == null)
                return 0;
            return Math.ceil($scope.message.length / 160);
        };

        $scope.getCreditsRequired = function () {
            if ($scope.message == null)
                return 0;
            return ($scope.getSingleMessageCredit() * $scope.totalSMS());
        };

        $scope.totalSMS = function () {

            var countSMS = 0;
            angular.forEach($scope.selectedStudents, function (selectedStudent) {
                countSMS += selectedStudent.mobileCount;
            })

            angular.forEach($scope.selectedTeachers, function (selectedTeacher) {
                countSMS += selectedTeacher.mobileCount;
            })

            if ($scope.sendCopy)
                countSMS += 1;

            return countSMS;
        }


        $scope.getPeopleCount = function () {

            if ($scope.sendCopy)
                return $scope.selectedStudents.length + $scope.selectedTeachers.length + 1;
            return $scope.selectedStudents.length + $scope.selectedTeachers.length;

        }

        $scope.countSelectedClasses = function () {
            if (!Array.isArray($scope.classes))
                return 0;

            //count selected classes
            var count = 0;
            for (var i = 0; i < $scope.classes.length; ++i) {
                if ($scope.classes[i].selected)
                    count++;
            }

            return count;
        }

        $scope.countSelectedDepartments = function () {
            if (!Array.isArray($scope.departments))
                return 0;

            //count selected departments
            var count = 0;
            for (var i = 0; i < $scope.departments.length; ++i) {
                if ($scope.departments[i].selected)
                    count++;
            }
            return count;
        }

        $scope.countSelectedSearchResult = function () {
            if (!Array.isArray($scope.pagedItems))
                return 0;

            //count selected search result
            var count = 0;
            console.log($scope.pagedItems);
            for (var i = 0; i < $scope.pagedItems.length; ++i) {
                for (var j = 0; j < $scope.pagedItems[i].length; ++j) {
                    if ($scope.pagedItems[i][j].searchPeople.selected) {
                        count = 1;
                        break;
                    }
                }
                if (count == 1)
                    break;
            }
            return count;
        }

        $scope.countSelectedEveningRoutes = function () {
            if (!Array.isArray($scope.eveningRoutes))
                return 0;

            //count selected departments
            var count = 0;
            for (var i = 0; i < $scope.eveningRoutes.length; ++i) {
                if ($scope.eveningRoutes[i].selected)
                    count++;
            }
            return count;
        }

        $scope.countSelectedMorningRoutes = function () {
            if (!Array.isArray($scope.morningRoutes))
                return 0;

            //count selected departments
            var count = 0;
            for (var i = 0; i < $scope.morningRoutes.length; ++i) {
                if ($scope.morningRoutes[i].selected)
                    count++;
            }
            return count;
        }

        $scope.countRoutes = function () {
            return $scope.countSelectedMorningRoutes() + $scope.countSelectedEveningRoutes();
        }


        $scope.addBySearchResult = function () {
            //get all selected classes
            for (var i = 0; i < $scope.pagedItems.length; ++i) {
                for (var j = 0; j < $scope.pagedItems[i].length; ++j) {
                    if ($scope.pagedItems[i][j].searchPeople.selected) {
                        if ($scope.pagedItems[i][j].isTeacher)
                            $scope.selectedTeachers.push($scope.pagedItems[i][j].searchPeople);
                        else
                            $scope.selectedStudents.push($scope.pagedItems[i][j].searchPeople);
                    }
                }
            }
            $scope.calculatePageItemsForUserAdded(true, true);
            if ($scope.selectedStudents.length > 0 || $scope.selectedTeachers.length > 0) {
                $scope.studentAndTeacherList = true;
            }
            console.log($scope.selectedStudents);
            console.log($scope.selectedTeachers);
        }

        $scope.addByClass = function () {
            var selectedClasses = [];

            //get all selected classes
            for (var i = 0; i < $scope.classes.length; ++i) {
                if ($scope.classes[i].selected)
                    selectedClasses.push($scope.classes[i].class);
            }

            //make post call for students
            $http.post(
                '/student/getStudentCodes',
                {"classSection": selectedClasses}
            ).success(function (data) {
                    if (Array.isArray(data)) {
                        $scope.selectedTeachers = [];
                        $scope.selectedStudents = data;
                        $scope.selectedStudents = $scope.selectedStudents.unique();
                        $scope.calculatePageItemsForUserAdded(true, false);
                        if ($scope.selectedStudents.length > 0 && $scope.selectedTeachers.length == 0) {
                            $scope.studentList = true;
                        }
                    }

                    //todo: log this as this is not an array
                }).error(function (e) {
                    log('error', e)
                });


        }

        $scope.addByDepartments = function () {
            var selectedDepartments = [];

            //get all selected departments
            for (var i = 0; i < $scope.departments.length; ++i) {
                if ($scope.departments[i].selected)
                    selectedDepartments.push($scope.departments[i].department);
            }

            //make post call for students
            $http.post(
                '/teacher/getTeacherCodes',
                {"departments": selectedDepartments}
            ).success(function (data) {
                    if (Array.isArray(data)) {
                        $scope.selectedStudents = [];
                        $scope.selectedTeachers = data;
                        $scope.selectedTeachers = $scope.selectedTeachers.unique();
                        $scope.calculatePageItemsForUserAdded(false, true);
                        if ($scope.selectedStudents.length == 0 && $scope.selectedTeachers.length > 0) {
                            $scope.teacherList = true;
                        }
                    }
                    //todo: log if not array
                }).error(function (e) {
                    log('error', e)
                });
        }


        $scope.addByBusRoutes = function () {
            var selectedMorningBusRoutes = [];
            var selectedEveningBusRoutes = [];

            //get all selected eveningRoutes
            for (var i = 0; i < $scope.eveningRoutes.length; ++i) {
                if ($scope.eveningRoutes[i].selected)
                    selectedEveningBusRoutes.push($scope.eveningRoutes[i].route);
            }
            //get all selected morningRoutes
            for (var i = 0; i < $scope.morningRoutes.length; ++i) {
                if ($scope.morningRoutes[i].selected)
                    selectedMorningBusRoutes.push($scope.morningRoutes[i].route);
            }

            //make a call to get students from busRoutes
            $http.post(
                '/school/get_students_or_teachers_from_bus_routes',
                {"morningBusRoutes": selectedMorningBusRoutes,
                    "eveningBusRoutes": selectedEveningBusRoutes
                }

            ).success(function (data) {

                    $scope.selectedStudents = data.students;
                    $scope.selectedTeachers = data.teachers;
                    $scope.calculatePageItemsForUserAdded(true, true);
                    if ($scope.selectedStudents.length > 0 || $scope.selectedTeachers.length > 0) {
                        $scope.studentAndTeacherList = true;
                    }
                    //todo: log this as this is not an array
                }).error(function (e) {
                    log('error', e)
                });

        }

        $scope.changeFilterSelection = function (selection) {
            bootbox.confirm("Are you sure you want to change the filter?", function (result) {
                checkValue(result, selection);
            });
        }

        function checkValue(isChanged, selection) {
            if (isChanged) {
                $scope.$apply(function () {
                    $scope.filterType = selection;
                    $scope.monitorFunction();
                    $scope.selectedStudents = [];
                    $scope.selectedTeachers = [];
                    $scope.pagedStudents = [];
                    $scope.pagedTeachers = [];
                    $scope.currentStudentListPage = 0;
                    $scope.currentTeacherListPage = 0;
                    $scope.studentList = false;
                    $scope.teacherList = false;
                    $scope.studentAndTeacherList = false;

                });
            }
            else {
                $scope.$apply(function () {
                    $scope.filterType = $scope.previousFilterSelected;
                });
            }
        }

        $scope.checkBeforeSend = function () {
            if ($scope.message == null)
                return true;
            if ($scope.sendCopy == true && $scope.totalSMS() == 1)
                return true;
            if ($scope.message.length > 320 || ($scope.getCreditsRequired() >= $scope.creditsAvailable) || $scope.message == null || $scope.totalSMS() <= 0 || $scope.queueMessageSuccess == true)
                return true;
            console.log($scope.creditsAvailable);
            return false;
        }


        $scope.queueSMS = function () {

            var templateId = $scope.templateSelected;
            if ($scope.templateSelected == 'custom')
                templateId = 0;
            templateId = $scope.templateSelected;
            //make post call for queue the message
            $http.post(
                '/sms/post_create',
                {"studentCodes": $scope.selectedStudents,
                    "teacherCodes": $scope.selectedTeachers,
                    "message": $scope.message,
                    "templateId": templateId,
                    "sendCopy": $scope.sendCopy,
                    "messageVars": $scope.model
                }
            ).success(function (data) {
                    if (data.status == true) {
                        $scope.errorSMS = false;
                        $scope.queueMessageSuccess = true;
                        if (data.result.numberOfCreditsUsed)
                            $scope.finalCreditUsed = data.result.numberOfCreditsUsed;
                        if (data.result.numberOfCreditsUsedTeachers)
                            $scope.finalCreditUsed += data.result.numberOfCreditsUsedTeachers;
                    }
                    else {
                        $scope.errorSMS = true;
                        $scope.errorMessage = data.message;
                    }
                    console.log(data);
//                    console.log(Array.isArray(data));
                    //todo: log this as this is not an array
                }).error(function (e) {
                    log('error', e)
                });
        }
    }
    ])
;