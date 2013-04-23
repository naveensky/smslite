<div class="row">
    <div class="span3">
        <div class="box" style="padding: 8px 0;">
            @render('teacher.leftmenu')
        </div>

        <div class="box padding-left-12">

            <label>Departments</label>
            <select ng-model="departments" multiple="multiple">
                <option ng-repeat="totaldepartment in totaldepartments">{{totaldepartment}}</option>
            </select>
            <label>MorningBusRoute</label>
            <select ng-model="morningRoutes" multiple="multiple">
                <option ng-repeat="morningroute in morningroutes">{{morningroute}}</option>
            </select>
            <label>EveningBusRoute</label>
            <select ng-model="eveningRoutes" multiple="multiple">
                <option ng-repeat="eveningroute in eveningroutes">{{eveningroute}}</option>
            </select>
            <button class="btn btn-primary" ng-click="getTeachers()">Filter</button>

        </div>
    </div>

    <div class="span9">
        <div class="box">

            <h3><i class="icon-th-list icon-large"></i>List Teachers</h3>

            <p>This is a list of all the teachers that meet the filter criteria that you have selected on the left.</p>
            <div class="row" ng-show="deleteTeacherSuccess" style="margin-left:0px">
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    {{deleteSuccessMessage}}
                </div>
            </div>
            <div class="row" ng-show="deleteTeacherError" style="margin-left:0px">
                <div class="alert alert-error margin-top-20">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    {{deleteErrorMessage}}
                </div>
            </div>

            <div class="row">

                <div class="span8">
                    <table class="table table-striped table-hover table-condensed">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Morning Bus Route</th>
                            <th>Evening Bus Route</th>
                            <th>Mobile</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>


                        </tr>
                        </thead>
                        <tbody ng-show="teachers.length>0">

                        <tr ng-repeat="teacher in teachers">
                            <td>{{ teacher.name }}</td>
                            <td>{{ teacher.department }}</td>
                            <td>{{ teacher.morningBusRoute }}</td>
                            <td>{{ teacher.eveningBusRoute }}</td>
                            <td><a href
                                   title="{{getMobileNumbers(teacher)}}">{{teacher.mobile1}}</a></td>
                            <td><a href="#/teacher/edit/{{teacher.code}}">Edit</a></td>
                            <td><a ng-click="deleteTeacher($index)">Delete</a></td>
                        </tr>
                        </tbody>
                        <tbody ng-show="teachers.length==0">
                        <tr>
                            <td colspan="9" style="text-align: center">
                                <br/>
                                <strong>
                                    No Data Found
                                </strong>
                                <br/>
                                <br/>
                            </td>
                        </tr>
                        </tbody>

                    </table>
                    <div>
                        <button class="btn" ng-disabled="previousPage == 0" ng-click="updatePrevious()"><i
                                class="icon-caret-left icon-large"></i></button>
                        <button class="btn" ng-disabled="teachers.length ==0" ng-click="updateNext()"><i
                                class="icon-caret-right icon-large"></i></button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

