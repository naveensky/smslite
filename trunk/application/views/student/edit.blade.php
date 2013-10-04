<div class="row">
    <div class="span3">
        <div class="box" style="padding: 8px 0;">
            @render('student.editleftmenu')
        </div>
    </div>
    <div class="span9">
        <div class="box">
            <h3><i class="icon-th-list icon-large"></i>Edit Student</h3>

            <form name="form" novalidate="novalidate">
                <div class="row">
                    <div class="span4">
                        <label for="admission">Admission Number</label>
                        <input type="text" ng-model="studentData.uniqueIdentifier" name="admission"
                               id="admission"
                               placeholder="Enter admission number"
                               class="span4">
                        <span ng-show="form.admission.$error.required && !form.admission.$pristine "
                              class="validation invalid"><i class="icon-remove padding-right-5"></i>Admission Number is required</span>
                    </div>
                    <div class="span4">
                        <label for="name">Name</label>
                        <input type="text" ng-model="studentData.name" name="studentName" ng-required="true"
                               id="name"
                               placeholder="Enter full name"
                               class="span4">
                        <span ng-show="form.studentName.$error.required && !form.studentName.$pristine "
                              class="validation invalid"><i
                                class="icon-remove padding-right-5"></i>Name is required</span>
                    </div>

                </div>
                <div class="row">
                    <div class="span4">
                        <label for="class">Class Standard</label>
                        <input type="text" ng-model="studentData.classStandard" name="class"
                               id="class"
                               placeholder="Enter student class"
                               class="span4">
                    </div>
                    <div class="span4">
                        <label for="section">Class Section</label>
                        <input type="text" ng-model="studentData.classSection" name="section"
                               id="section"
                               placeholder="Enter student section"
                               class="span4">
                    </div>
                    <div class="span4">
                        <label for="email">Email</label>
                        <input type="email" ng-model="studentData.email" name="email"
                               id="email"
                               placeholder="Enter email"
                               class="span4">
                    </div>
                    <div class="span4">
                        <label for="dob">DOB</label>

                        <div class="input-append date datetime-input" data-date-format="dd M yyyy hh:ii">
                            <input size="16" type="text" class="span4 student-dob" id="dob"
                                   value="{{getFormattedDate(studentData.dob)}}"
                                   readonly>
                            <span class="add-on"><i class="icon-calendar"></i></span>
                        </div>
                    </div>

                    <div class="span4">
                        <label for="gender">Gender</label>
                        <select name="gender" id="gender" class="gender-select" ng-model="studentData.gender">
                            <option>Male</option>
                            <option>Female</option>
                        </select>
                    </div>
                    <div class="span4">
                        <label for="mobile1">Mobile1</label>
                        <input name="mobile1" type="text" ng-model="studentData.mobile1" ng-required="true"
                               ng-minLength="8"
                               id="mobile1" class="span4" placeholder="Mobile1">
                        <span ng-show="form.mobile1.$invalid && !form.mobile1.$pristine" class="text-error">The mobile number should be atleast 8 digits </span>
                    </div>

                </div>
                <div class="row">

                    <div class="span4">
                        <label for="mobile2">Mobile2</label>
                        <input name="mobile2" type="text" ng-model="studentData.mobile2"

                               id="mobile2" class="span4" placeholder="Mobile2">

                    </div>
                    <div class="span4">
                        <label for="mobile3">Mobile3</label>
                        <input name="mobile3" type="text" ng-model="studentData.mobile3"

                               id="mobile3" class="span4" placeholder="Mobile3">

                    </div>
                </div>
                <div class="row">


                    <div class="span4">
                        <label for="mobile4">Mobile4</label>
                        <input name="mobile4" type="text" ng-model="studentData.mobile4"
                               id="mobile4" class="span4" placeholder="Mobile4">

                    </div>
                    <div class="span4">
                        <label for="mobile5">Mobile5</label>
                        <input name="mobile5" type="text" ng-model="studentData.mobile5"
                               id="mobile5" class="span4" placeholder="Mobile5">

                    </div>
                </div>
                <div class="row">
                    <div class="span4">
                        <label for="morning">Morning Bus Route</label>
                        <input type="text" ng-model="studentData.morningBusRoute" name="morning"
                               id="email"
                               placeholder="Enter morning bus route"
                               class="span4">
                    </div>
                    <div class="span4">
                        <label for="evening">Evening Bus Route</label>
                        <input type="text" ng-model="studentData.eveningBusRoute" name="evening"
                               id="email"
                               placeholder="Enter evening bus route"
                               class="span4">
                    </div>
                </div>
                <div class="row">
                    <div class="span4">
                        <label for="father">Father Name</label>
                        <input type="text" ng-model="studentData.fatherName" name="fathername"
                               id="father"
                               placeholder="Enter father name"
                               class="span4">
                    </div>
                    <div class="span4">
                        <label for="mother">Mother Name</label>
                        <input type="text" ng-model="studentData.motherName" name="mothername"
                               id="mother"
                               placeholder="Enter mother name"
                               class="span4">
                    </div>
                </div>
                <div class="row">
                    <div class="alert alert-success margin-top-20 user-register" ng-show="showEditScreenError">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        {{errorEditMessage}}
                    </div>
                    <div class="span4">
                        <button type="button" ng-disabled="form.$invalid" ng-click="saveStudentData()"
                                class="btn btn-success">Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    initComponents();
</script>
