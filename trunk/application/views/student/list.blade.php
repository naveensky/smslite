<div class="row">
    <div class="span3">
        <div class="box" style="padding: 8px 0;">
            @render('student.leftmenu')

        </div>

        <div class="box padding-left-12">

                <label><strong>Classes</strong></label>
                <select ng-model="classSections" multiple="multiple">
                    @foreach($classes as $class)
                    <option><%$class%></option>
                    @endforeach
                </select>
                <label><strong>Morning Bus Routes</strong></label>
                <select ng-model="morningRoutes" multiple="multiple">
                    @foreach($morningRoutes as $morningRoute)
                    <option><%$morningRoute%></option>
                    @endforeach
                </select>
                <label><strong>Evening Bus Routes</strong></label>
                <select ng-model="eveningRoutes" multiple="multiple">
                    @foreach($eveningRoutes as $eveningRoute)
                    <option><%$eveningRoute%></option>
                    @endforeach
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
                            <th>Name</th>
                            <th>Class</th>
                            <th>Section</th>

                        </tr>
                        </thead>
                        <tbody ng-show="students.length>0">

                        <tr ng-repeat="student in students">
                            <td>{{ student.name }}</td>
                            <td>{{ student.classStandard }}</td>
                            <td>{{ student.classSection }}</td>

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
</div>
