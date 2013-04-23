<div class="row">
    <div class="span3">
        <div class="box" style="padding: 8px 0;">
            @render('teacher.leftmenu')
        </div>
    </div>
    <div class="span9">
        <div class="box">
            <h3><i class="icon-th-list icon-large"></i>Add teacher</h3>

            <form name="form" novalidate="novalidate">
                <div class="row">
                    <div class="span4">
                        <label for="name">Name</label>
                        <input type="text" ng-model="addTeacherData.name" name="teacherName" ng-required="true"
                               id="name"
                               placeholder="Enter full name"
                               class="span4">
                        <span ng-show="form.teacherName.$error.required && !form.teacherName.$pristine "
                              class="validation invalid"><i
                                class="icon-remove padding-right-5"></i>Name is required</span>
                    </div>
                    <div class="span4">
                        <label for="email">Email</label>
                        <input type="email" ng-model="addTeacherData.email" name="email"
                               id="email"
                               placeholder="Enter email"
                               class="span4">
                    </div>

                </div>
                <div class="row">
                    <div class="span4">
                        <label for="department">Department</label>
                        <input type="text" ng-model="addTeacherData.department" name="email"
                               id="department"
                               placeholder="Enter email"
                               class="span4">
                    </div>
                    <div class="span4">
                        <label for="dob">DOB</label>

                        <div class="input-append date datetime-input" data-date-format="dd M yyyy">
                            <input size="16" type="text" class="span4 student-dob" id="dateofbirth"
                                   readonly>
                            <span class="add-on"><i class="icon-calendar"></i></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="span4">
                        <label for="gender">Gender</label>
                        <select name="gender" id="gender" class="gender-select" ng-model="addTeacherData.gender">
                            <option>Male</option>
                            <option>Female</option>
                        </select>
                    </div>


                    <div class="span4">
                        <label for="mobile1">Mobile1</label>
                        <input name="mobile1" type="text" ng-model="addTeacherData.mobile1" ng-required="true"
                               ng-minLength="8"
                               id="mobile1" class="span4" placeholder="Mobile1">
                        <span ng-show="form.mobile1.$invalid && !form.mobile1.$pristine" class="text-error">The mobile number should be atleast 8 digits </span>
                    </div>
                </div>
                <div class="row">
                    <div class="span4">
                        <label for="mobile2">Mobile2</label>
                        <input name="mobile2" type="text" ng-model="addTeacherData.mobile2"

                               id="mobile2" class="span4" placeholder="Mobile2">

                    </div>
                    <div class="span4">
                        <label for="mobile3">Mobile3</label>
                        <input name="mobile3" type="text" ng-model="addTeacherData.mobile3"

                               id="mobile3" class="span4" placeholder="Mobile3">

                    </div>
                </div>
                <div class="row">
                    <div class="span4">
                        <label for="mobile4">Mobile4</label>
                        <input name="mobile4" type="text" ng-model="addTeacherData.mobile4"
                               id="mobile4" class="span4" placeholder="Mobile4">

                    </div>
                    <div class="span4">
                        <label for="mobile5">Mobile5</label>
                        <input name="mobile5" type="text" ng-model="addTeacherData.mobile5"
                               id="mobile5" class="span4" placeholder="Mobile5">

                    </div>
                </div>
                <div class="row">
                    <div class="span4">
                        <label for="morning">Morning Bus Route</label>
                        <input type="text" ng-model="addTeacherData.morningBusRoute" name="morning"
                               id="email"
                               placeholder="Enter morning bus route"
                               class="span4">
                    </div>
                    <div class="span4">
                        <label for="evening">Evening Bus Route</label>
                        <input type="text" ng-model="addTeacherData.eveningBusRoute" name="evening"
                               id="email"
                               placeholder="Enter evening bus route"
                               class="span4">
                    </div>
                </div>
                <div class="row">
                    <div class="alert alert-error margin-top-20 user-register" ng-show="showAddScreenError">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        {{errorAddMessage}}
                    </div>
                    <div class="span4">
                        <button type="button" ng-disabled="form.$invalid" ng-click="addNewTeacher()"
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
