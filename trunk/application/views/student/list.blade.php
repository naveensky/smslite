<div class="row">
    <div class="span3">
        <div class="box" style="padding: 8px 0;">
            @render('student.leftmenu')

        </div>

        <div class="box padding-left-12">

            <label><strong>Classes</strong></label>
            <select ng-model="classSections" multiple="multiple">
                <option ng-repeat="class in classes">{{class}}</option>
            </select>
            <label><strong>Morning Bus Routes</strong></label>
            <select ng-model="morningRoutes" multiple="multiple">
                <option ng-repeat="morningroute in morningroutes">{{morningroute}}</option>
            </select>
            <label><strong>Evening Bus Routes</strong></label>
            <select ng-model="eveningRoutes" multiple="multiple">
                <option ng-repeat="eveningroute in eveningroutes">{{eveningroute}}</option>
            </select>
            <button class="btn btn-primary" ng-click="filterStudents()">Filter</button>
        </div>

    </div>

    <div class="span9">
        <div class="box">

            <h3><i class="icon-th-list icon-large"></i>List Students</h3>

            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum
                has been
                the industry's standard dummy text ever since the 1500s, when an unknown printer took a
                galley
                of type and scrambled it to make a type specimen book.</p>

            <div class="row">

                <div class="span8">
                    <table class="table table-striped table-hover table-condensed">
                        <thead>
                        <tr>
                            <th>Admission No.</th>
                            <th>Name</th>
                            <th>Class</th>
                            <th>Morning Bus Route</th>
                            <th>Evening Bus Route</th>
                            <th>Mobile</th>

                        </tr>
                        </thead>
                        <tbody ng-show="students.length>0">

                        <tr ng-repeat="student in students">
                            <td>{{ student.uniqueIdentifier }}</td>
                            <td>{{ student.name }}</td>
                            <td>{{ student.classStandard }}-{{ student.classSection }}</td>
                            <td>{{ student.morningBusRoute }}</td>
                            <td>{{ student.eveningBusRoute }}</td>
                            <td><a href
                                   title="{{getMobileNumbers(student)}}">{{student.mobile1}}</a></td>

                        </tr>

                        </tbody>
                        <tbody ng-show="students.length==0">
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
                        <button class="btn" ng-disabled="students.length ==0" ng-click="updateNext()"><i
                                class="icon-caret-right icon-large"></i></button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
