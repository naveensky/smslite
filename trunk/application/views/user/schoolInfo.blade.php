<section class="container" style="margin-top:20px;">
    <div class="box">
        <!--<div class="tabbable">-->

        <!-- Tabs
       ================================================== -->
        <ul class="nav nav-tabs" id="register-tabs">
            <li><a href="#">Sign up</a></li>
            <li class="active"><a href="#">School Information</a></li>
            <li><a href="#">Verify Mobile</a></li>
            <li><a href="#">Verify Email</a></li>
            <li><a href="#">Welcome</a></li>
        </ul>
        <!-- / Tabs -->

        <!-- Tabs content
       ================================================== -->
        <div class="tab-content">

            <!-- Recent comments tab content -->
            <div class="tab-pane fade in active" id="school-information">

                <form name="form" novalidate class="form-horizontal offset2">
                    <div class="control-group">
                        <label class="control-label" for="inputSchoolName">School Name</label>

                        <div class="controls">
                            <input type="text" id="inputSchoolName" ng-required="true" name="schoolName" ng-model="schoolName" placeholder="School Name">
                            <span ng-show="form.schoolName.$error.required && !form.schoolName.$pristine "
                                  class="validation invalid"><i class="icon-remove padding-right-5"></i>School name is required</span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="inputContactPerson">Contact Person</label>

                        <div class="controls">
                            <input type="text" id="inputContactPerson" ng-required="true" name="contactPerson" ng-model="contactPerson" placeholder="Contact Person">
                            <span ng-show="form.contactPerson.$error.required && !form.contactPerson.$pristine "
                                  class="validation invalid"><i class="icon-remove padding-right-5"></i>Contact person name is required</span>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="inputAddress">Address</label>

                        <div class="controls">
                            <textarea rows="3" id="inputAddress" name="address" ng-required="true" ng-model="address" placeholder="Address"></textarea>
                            <span ng-show="form.address.$error.required && !form.address.$pristine "
                                  class="validation invalid"><i class="icon-remove padding-right-5"></i>Address is required</span>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="inputSchoolContactNumber">School Contact Number</label>
                        <div class="controls">
                            <input type="text" id="inputSchoolContactNumber" name="schoolContact" ng-required="true" ng-model="schoolContactNumber" ng-minLength="8" ng-pattern="/^\+{0,1}\d+$/" placeholder="School Contact Number">
                            <span ng-show="form.schoolContact.$error.required && !form.schoolContact.$pristine"
                                  class="validation invalid"><i class="icon-remove padding-right-5"></i>Contact number is required</span>
                            <span ng-show="form.schoolContact.$invalid && !form.schoolContact.$pristine && !form.schoolContact.$error.required" class="validation invalid"><i
                                    class="icon-remove padding-right-5"></i>The contact number must be at least 8 digits</span>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="inputCity">City</label>

                        <div class="controls">
                            <input type="text" id="inputCity" ng-required="true" name="city" ng-model="city" placeholder="City">
                            <span ng-show="form.city.$error.required && !form.city.$pristine"
                                  class="validation invalid"><i class="icon-remove padding-right-5"></i>City is required</span>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="inputState">State</label>

                        <div class="controls">
                            <input type="text" id="inputState" ng-required="true" name="state" ng-model="state" placeholder="State">
                            <span ng-show="form.state.$error.required && !form.state.$pristine"
                                  class="validation invalid"><i class="icon-remove padding-right-5"></i>State is required</span>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="inputZip">Zip</label>

                        <div class="controls">
                            <input type="text" id="inputZip" ng-required="true" name="zip" ng-model="zip" placeholder="Zip">
                            <span ng-show="form.zip.$error.required && !form.zip.$pristine"
                                  class="validation invalid"><i class="icon-remove padding-right-5"></i>Pin Code is required</span>
                        </div>
                    </div>
                    <input type="hidden" ng-init="senderId='<?php echo $senderId;?>'">

                    <div class="controls margin-top-20">
                        <button type="button" ng-click="saveSchoolInfo()" ng-disabled="form.$invalid" class="btn btn-success">Next</button>
                        <div class="alert alert-success margin-top-20 user-register" ng-show="errorUpdatingSchoolInfo">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            {{errorUpdateSchoolMessage}}
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- / Tabs content -->
    </div>
    <!--</div>-->
</section>