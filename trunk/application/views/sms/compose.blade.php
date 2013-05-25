<div ng-controller="Sms_Compose">
<div class="row" ng-show="queueMessageSuccess" style="margin-left:0px">
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <h5><i class="icon-ok-sign"></i> Message Successfully Queued</h5>
        Total credits consumed {{finalCreditUsed}}<br/>
        <a href="#/sms">Click here to send new sms</a>
    </div>
</div>
<div class="row" ng-show="errorSMS" style="margin-left:0px">
    <div class="alert alert-error margin-top-20">
        <button type="button" class="close" data-dismiss="alert">×</button>
        {{errorMessage}}
    </div>
</div>
<div class="row">
<div class="span3">
    <div class="box">
        <h3><i class="icon-group icon-large"></i> Select People</h3>
        <label>Choose Filter</label>
        <select class="filter" ng-model="filterType" ng-change="changeFilterSelection(filterType)">
            <option value="classFilter">By Classes</option>
            <option value="routeFilter">By Bus Routes</option>
            <option value="departmentFilter">By Departments</option>
            <option value="individualFilter">Search Individual</option>
        </select>

        <hr>
        <div id="filter-individual" ng-show="filterType=='individualFilter'">
            <label>Search by Name, Mobile or Admission No. </label>

            <div class="input-append">
                <input type="text" ng-model="searchValue" class="span2">
                <button class="btn" ng-disabled="searchValue.length==0" ng-click="searchPeople()" type="button">
                    <i
                        class="icon-search"></i></button>
            </div>
            <table ng-show="searchResults.length>0" class="table table-condensed">
                <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th>Name</th>
                    <th>Admission No.</th>
                </tr>
                </thead>
                <tfoot>
                <td colspan="6">
                    <div class="pagination pull-right">
                        <ul>
                            <li ng-class="{disabled: currentPage == 0}">
                                <a href ng-click="prevPage()">«</a>
                            </li>
                            <li ng-class="{disabled: currentPage == pagedItems.length - 1}">
                                <a href ng-click="nextPage()">»</a>
                            </li>
                        </ul>
                    </div>
                </td>
                </tfoot>
                <tbody>
                <tr ng-class="getStatusCss(people)" ng-repeat="people in pagedItems[currentPage]">
                    <td><input ng-model="people.searchPeople.selected" type="checkbox"></td>
                    <td>{{people.searchPeople.name}}</td>
                    <td>{{people.searchPeople.admissionNumber}}</td>
                </tr>
                </tbody>
            </table>
            <button ng-disabled="countSelectedSearchResult()==0" ng-click="addBySearchResult()"
                    ng-show="searchResults.length>0" class="btn">Add to List
            </button>
        </div>
        <div id="filter-class" ng-show="filterType=='classFilter'">
            <div class="control-group scrollfix">
                <label class="control-label">Choose Classes</label>

                <div ng-repeat="classVar in classes">
                    <label class="checkbox">
                        <input type="checkbox" ng-model="classVar.selected">{{classVar.class}}
                    </label>
                </div>
            </div>

            <div class="control-group">
                <button class="btn" ng-disabled="countSelectedClasses()==0" ng-click="addByClass()">Add to List
                </button>
            </div>


        </div>
        <div id="filter-department" ng-show="filterType=='departmentFilter'">
            <label>Choose Departments</label>

            <div ng-repeat="department in departments">
                <label class="checkbox">
                    <input type="checkbox" ng-model="department.selected">{{department.department}}
                </label>
            </div>

            <div class="control-group">
                <button class="btn" ng-disabled="countSelectedDepartments()==0" ng-click="addByDepartments()">
                    Add to
                    List
                </button>
            </div>
        </div>
        <div id="filter-route" ng-show="filterType=='routeFilter'">
            <label>Choose Morning Routes</label>

            <div ng-repeat="route in morningRoutes">
                <label class="checkbox">
                    <input type="checkbox" ng-model="route.selected">{{route.route}}
                </label>
            </div>
            <hr>
            <label>Choose Evening Routes</label>

            <div ng-repeat="route in eveningRoutes">
                <label class="checkbox">
                    <input type="checkbox" ng-model="route.selected">{{route.route}}
                </label>
            </div>
            <div class="control-group">
                <button class="btn" ng-disabled="countRoutes()==0" ng-click="addByBusRoutes()">Add to List
                </button>
            </div>
        </div>

    </div>
</div>
<div class="span6">
    <div class="box">
        <h3><i class="icon-envelope-alt icon-large"></i>Compose Message</h3>
        <label>Choose Template</label>
        <select class="template-select" ng-model="templateSelected" ng-change="templateMessage()"
                ng-options="template.id as template.name for template in templates "></select>
        <textarea ng-disabled="checkTemplateSelected()" class="input-block-level" rows="5" ng-model="message"
                  ng-required="true"
                  placeholder="enter your message here..."></textarea>
            <span ng-show="message.length>0" class="help-block">
                <i>
                    {{message.length}} character, {{getSingleMessageCredit()}} credit(s) required per person to
                    send this text.
                </i>
            </span>
            <span ng-show="message.length>320" class="text-error">
                <i>
                    maximum character limit exceeded {{320-message.length}}
                </i>
            </span>

    </div>
    <div class="box" ng-hide="!checkTemplateSelected()">
        <h3>Placeholders</h3>

        <div ng-include="link"></div>
    </div>
</div>
<div class="span3">
<div class="box">
    <h3><i class="icon-cog icon-large"></i>Verify &amp; Send</h3>

    <div class="control-group">
        <label>Total Individual</label>
        <input class="input-block-level" type="text" readonly="readonly" value="{{getPeopleCount()}}">
    </div>
    <div class="control-group">
        <label>Total SMS to deliver</label>
        <input class="input-block-level" type="text" readonly="readonly" value="{{totalSMS()}}">
    </div>
    <div class="control-group">
        <label>Credits Required</label>
        <input class="input-block-level" type="text" readonly="readonly" value="{{getCreditsRequired()}}">
    </div>
    <div class="control-group">
        <label>Credits Available <a href="#" class="pull-right">
                <small><a style="float:right" href="http://msngr.in/pricing" target="_blank"><i
                            class="icon-money"></i> Buy
                        Credits</small>
            </a></label>

        <input class="input-block-level" type="text" readonly="readonly" ng-model="creditsAvailable">
    </div>
    <div class="control-group">
        <label class="checkbox">
            <input type="checkbox" ng-model="sendCopy">Send Copy to Admin
        </label>
        <button ng-disabled="checkBeforeSend()" ng-click="queueSMS()" class="btn btn-block btn-success"><i
                class="icon-add"></i> Add to SMS Queue
        </button>
    </div>
</div>
<div class="box" ng-show="studentList">
    <h3><i class="icon-cog icon-large"></i>Selected Students List</h3>
    <table class="table table-condensed">
        <thead>
        <tr>
            <th>Name</th>
            <th>Class</th>
        </tr>
        </thead>
        <tfoot>
        <td colspan="6">
            <div class="pagination pull-right">
                <ul>
                    <li ng-class="{disabled: currentStudentListPage == 0}">
                        <a href ng-click="prevPageStudent()">«</a>
                    </li>
                    <li ng-class="{disabled: currentStudentListPage == pagedStudents.length - 1}">
                        <a href ng-click="nextPageStudent()">»</a>
                    </li>
                </ul>
            </div>
        </td>
        </tfoot>
        <tbody>
        <tr ng-repeat="selectedStudent in pagedStudents[currentStudentListPage]">
            <td>{{selectedStudent.name}}</td>
            <td>{{selectedStudent.classStandard}}-{{selectedStudent.classSection}}</td>
        </tr>
        </tbody>
    </table>
</div>
<div class="box" ng-show="teacherList">
    <h3><i class="icon-cog icon-large"></i>Selected Teachers List</h3>
    <table class="table table-condensed">
        <thead>
        <tr>
            <th>Name</th>
            <th>Department</th>
        </tr>
        </thead>
        <tfoot>
        <td colspan="6">
            <div class="pagination pull-right">
                <ul>
                    <li ng-class="{disabled: currentTeacherListPage == 0}">
                        <a href ng-click="prevPageTeacher()">«</a>
                    </li>
                    <li ng-class="{disabled: currentTeacherListPage == pagedTeachers.length - 1}">
                        <a href ng-click="nextPageTeacher()">»</a>
                    </li>
                </ul>
            </div>
        </td>
        </tfoot>
        <tbody>
        <tr ng-repeat="selectedTeacher in pagedTeachers[currentTeacherListPage]">
            <td>{{selectedTeacher.name}}</td>
            <td>{{selectedTeacher.department}}</td>
        </tr>
        </tbody>
    </table>
</div>
<div class="box" ng-show="studentAndTeacherList">
    <h3><i class="icon-cog icon-large"></i>Selected Users List</h3>

    <div class="well clearfix">
        <div class="accordion" id="accordion2">

            <div class="accordion-group">
                <div class="accordion-heading">
                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion2"
                       href="#collapseOne">
                        Students List
                    </a>
                </div>
                <div id="collapseOne" class="accordion-body collapse" style="height: 0px;">
                    <div class="accordion-inner">
                        <table class="table table-condensed">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Class</th>
                            </tr>
                            </thead>
                            <tfoot ng-show="pagedStudents.length>0">
                            <td colspan="6">
                                <div class="pagination pull-right">
                                    <ul>
                                        <li ng-class="{disabled: currentStudentListPage == 0}">
                                            <a href ng-click="prevPageStudent()">«</a>
                                        </li>
                                        <li ng-class="{disabled: currentStudentListPage == pagedStudents.length - 1}">
                                            <a href ng-click="nextPageStudent()">»</a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                            </tfoot>
                            <tbody ng-show="pagedStudents.length>0">
                            <tr ng-repeat="selectedStudent in pagedStudents[currentStudentListPage]">
                                <td>{{selectedStudent.name}}</td>
                                <td>{{selectedStudent.classStandard}}-{{selectedStudent.classSection}}</td>
                            </tr>
                            </tbody>
                            <tbody ng-show="pagedStudents.length==0">
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
                    </div>
                </div>
            </div>

            <div class="accordion-group">
                <div class="accordion-heading">
                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion2"
                       href="#collapseTwo">
                        Teachers List
                    </a>
                </div>
                <div id="collapseTwo" class="accordion-body collapse" style="height: 0px;">
                    <div class="accordion-inner">
                        <table class="table table-condensed">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Department</th>
                            </tr>
                            </thead>
                            <tfoot ng-show="pagedTeachers.length>0">
                            <td colspan="6">
                                <div class="pagination pull-right">
                                    <ul>
                                        <li ng-class="{disabled: currentTeacherListPage == 0}">
                                            <a href ng-click="prevPageTeacher()">«</a>
                                        </li>
                                        <li ng-class="{disabled: currentTeacherListPage == pagedTeachers.length - 1}">
                                            <a href ng-click="nextPageTeacher()">»</a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                            </tfoot>
                            <tbody ng-show="pagedTeachers.length>0">
                            <tr ng-repeat="selectedTeacher in pagedTeachers[currentTeacherListPage]">
                                <td>{{selectedTeacher.name}}</td>
                                <td>{{selectedTeacher.department}}</td>
                            </tr>
                            </tbody>
                            <tbody ng-show="pagedTeachers.length==0">
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
                    </div>
                </div>
            </div>

        </div>
    </div>


</div>
</div>
</div>
</div>