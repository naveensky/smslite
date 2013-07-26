<div class="row">
    <div class="span3">
        <div class="box" style="padding: 8px 0;">
            @render('syncdata.leftmenu')
        </div>
    </div>

    <div class="span9">
        <div class="box">
            <h3><i class="icon-exchange  icon-large"></i>Data Sync</h3>

            <div class="row">
                <div class="span8">
                    <div class="alert alert-info margin-top-20" style="width:45%"
                         ng-show="showSync">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <i class="icon-spinner icon-spin icon-large"></i> synchronization is in progress please wait...
                    </div>
                    <div class="alert alert-error margin-top-20" style="width:45%"
                         ng-show="serverError">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        {{serverErrorMessage}}
                    </div>
                    <form name="form" novalidate>

                        <label for="inputAPIKey">API Key</label>
                        <input type="text" class="span4" id="inputAPIKey" ng-required="true" name="APIKey"
                               ng-model="APIKey" placeholder="Enter API Key">
                            <span ng-show="form.APIKey.$error.required && !form.APIKey.$pristine "
                                  class="validation invalid"><i class="icon-remove padding-right-5"></i>API Key is required</span>

                        <label for="inputContactPerson">API URL For Students</label>
                        <input type="text" class="span4" id="inputStudentUrl" ng-required="true" name="studentURL" ng-pattern="/(((http|https):\/\/)|www\.)[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&:/~\+#!]*[\w\-\@?^=%&/~\+#])?/"
                               ng-model="studentAPIUrl" placeholder="Enter Student API URL">
                            <span ng-show="form.studentURL.$error.required && !form.studentURL.$pristine "
                                  class="validation invalid"><i class="icon-remove padding-right-5"></i>Students API URL is required</span>
                        <span
                            ng-show="form.studentURL.$invalid && !form.studentURL.$pristine && !form.studentURL.$error.required"
                            class="validation invalid"><i
                                class="icon-remove padding-right-5"></i>Please enter correct url format</span>
                        <span class="alert alert-error margin-top-20" style="width:45%"
                              ng-show="studentUrlError">
                              {{studentUrlErrorMessage}}
                        </span>

                        <label for="inputContactPerson">API URL For Teachers</label>
                        <input type="text" class="span4" id="inputTeacherUrl" ng-required="true" name="teacherURL" ng-pattern="/(((http|https):\/\/)|www\.)[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&:/~\+#!]*[\w\-\@?^=%&/~\+#])?/"
                               ng-model="teacherAPIUrl" placeholder="Enter Teacher API URL">
                            <span ng-show="form.teacherURL.$error.required && !form.teacherURL.$pristine "
                                  class="validation invalid"><i class="icon-remove padding-right-5"></i>Teachers API URL is required</span>
                        <span
                            ng-show="form.teacherURL.$invalid && !form.teacherURL.$pristine && !form.teacherURL.$error.required"
                            class="validation invalid"><i
                                class="icon-remove padding-right-5"></i>Please enter correct url format</span>
                        <span class="alert alert-error margin-top-20" style="width:45%"
                              ng-show="teacherUrlError">
                              {{teacherUrlErrorMessage}}
                        </span>

                        <div class="controls">
                            <label class="checkbox">
                                <input type="checkbox" ng-model="fetchStudents" ng-click="removeError()">Fetch Students
                        </div>

                        <div class="controls">
                            <label class="checkbox">
                                <input type="checkbox" ng-model="fetchTeachers" ng-click="removeError()">Fetch Teachers
                        </div>

                        <div class="alert alert-error margin-top-20" style="width:45%"
                             ng-show="fetchValidationError">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            {{fetchValidationErrorMessage}}
                        </div>

                        <div class="controls margin-top-20">

                            <button type="button" ng-click="saveAndSync()"
                                    ng-disabled="form.$invalid || showSync || disableSaveAndSync"
                                    class="btn btn-success">
                                Save & Sync
                            </button>

                        </div>
                    </form>
                    <div ng-show="studentImportStatusBlock">
                        <h5>Student Status</h5>

                        <p>No. of students imported {{getImportedStudents()}}</p>

                        <p>No. of students updated {{getUpdatedStudents()}}</p>

                        <p style="text-align: justify">No. of students Unable to import {{studentImportKeys}}</p>
                    </div>

                    <div ng-show="teacherImportStatusBlock">
                        <h5>Teacher Status</h5>

                        <p>No. of teachers imported {{getImportedTeachers()}}</p>

                        <p>No. of teachers updated {{getUpdatedTeachers()}}</p>

                        <p style="text-align: justify">No. of teachers unable to import {{teacherImportKeys}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

