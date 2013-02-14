<div class="row">
    <div class="span3">
        <div class="box" style="padding: 8px 0;">
            @render('teacher.leftmenu')
        </div>
    </div>

    <div class="span9">
        <div class="box">

            <h3><i class="icon-th-list icon-large"></i>List Teachers</h3>

            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum
                has been
                the industry's standard dummy text ever since the 1500s, when an unknown printer took a
                galley
                of type and scrambled it to make a type specimen book.</p>

            <div class="row">
                <div class="span3 border-right">

                    <!--                        <label>Label name</label>-->
                    <!--                        <input type="text" placeholder="Enter Your Name">-->
                    <label>Departments</label>
                    <select ng-model="departments" multiple="multiple">
                        @foreach($departments as $department)
                        <option><%$department%></option>
                        @endforeach
                    </select>
                    <label>MorningBusRoute</label>
                    <select ng-model="morningRoutes" multiple="multiple">
                        @foreach($morningRoutes as $morningRoute)
                        <option><%$morningRoute%></option>
                        @endforeach
                    </select>
                    <label>EveningBusRoute</label>
                    <select ng-model="eveningRoutes" multiple="multiple">
                        @foreach($eveningRoutes as $eveningRoute)
                        <option><%$eveningRoute%></option>
                        @endforeach
                    </select>
                    <button class="btn btn-success" ng-click="getTeachers()">Filter</button>

                </div>
                <div class="span5">
                    <table class="table table-striped table-hover table-condensed">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Mobile1</th>

                        </tr>
                        </thead>
                        <tbody ng-show="teachers.length>0">

                        <tr ng-repeat="teacher in teachers">
                            <td>{{ teacher.name }}</td>
                            <td>{{ teacher.department }}</td>
                            <td>{{ teacher.mobile1 }}</td>

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

                        <a ng-model="previousPage" ng-show="previousPage > 0" ng-hide="previousPage==0" ng-click="updatePrevious()" class="btn btn-info">
                   <span>
                       <span>Previous</span>
                   </span>
                        </a>

                        <a ng-model="nextPage" ng-click="updateNext()" ng-hide="teachers.length==0" class="btn btn-info">
                   <span>
                       <span> Next</span>
                   </span>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
</div>
