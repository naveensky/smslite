<section class="container" style="margin-top:20px;">
    <div class="row">
        <div class="span12">
            <div class="box">
                <h3><i class="icon-key icon-large"></i>Forgot Password</h3>

                <div class="tabbable">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#mobile" data-toggle="tab">Send by Mobile</a></li>
                        <li><a href="#email" data-toggle="tab">Send by Email</a></li>
                    </ul>

                    <div class="tab-content">
                        <!-- Recent users email-verify -->
                        <div class="tab-pane fade in active" id="mobile">

                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum
                                has been the industry's standard dummy text ever since the 1500s, when an unknown
                                printer took a galley of type and scrambled it to make a type specimen book.</p>

                            <form name="mobileForm" class="form-horizontal">

                                <div class="control-group">
                                    <label>Mobile Number</label>
                                    <input type="text" ng-model="mobileNumber" name="mobileNumber" ng-required="true"
                                           ng-minLength="8" ng-pattern="/^\+{0,1}\d+$/" placeholder="Mobile Number">
                                    <span
                                        ng-show="mobileForm.mobileNumber.$error.required && !mobileForm.mobileNumber.$pristine "
                                        class="validation invalid"><i class="icon-remove padding-right-5"></i>Please enter your mobile number</span>
                            <span
                                ng-show="mobileForm.mobileNumber.$invalid && !mobileForm.mobileNumber.$pristine && !mobileForm.mobileNumber.$error.required"
                                class="validation invalid"><i
                                    class="icon-remove padding-right-5"></i>The mobile number must be at least 8 digits</span>
                                </div>
                                <div class="control-group">
                                    <label>Email</label>
                                    <input type="email" ng-model="emailId" name="emailId" ng-required="true"
                                           placeholder="Email ID">
                                    <span ng-show="mobileForm.emailId.$error.required && !mobileForm.emailId.$pristine "
                                          class="validation invalid"><i class="icon-remove padding-right-5"></i>Please enter an email</span>
                            <span
                                ng-show="mobileForm.emailId.$error.email && !mobileForm.emailId.$pristine && !mobileForm.emailId.$error.required"
                                class="validation invalid"><i
                                    class="icon-remove padding-right-5"></i>Enter a valid email id. </span>
                                </div>
                                <div class="alert alert-error margin-top-20 forgot-password" ng-show="errorMobile">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    {{errorMobileMessage}}
                                </div>
                                <div class="alert alert-success margin-top-20 forgot-password" ng-show="successMobile">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    {{successMobileMessage}}
                                </div>
                                <div class="control-group">
                                    <button type="submit" ng-disabled="mobileForm.$invalid" ng-click="sendByMobile()"
                                            class="btn">Reset Password
                                    </button>
                                    <button type="reset" class="btn">Cancel</button>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade in" id="email">
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum
                                has been the industry's standard dummy text ever since the 1500s, when an unknown
                                printer took a galley of type and scrambled it to make a type specimen book.</p>

                            <form name="emailForm" novalidate class="form-horizontal">
                                <div class="control-group">
                                    <label>Enter Email ID</label>
                                    <input type="email" ng-model="email" name="email" ng-required="true"
                                           placeholder="Email ID">
<span ng-show="emailForm.email.$error.required && !emailForm.email.$pristine "
      class="validation invalid"><i class="icon-remove padding-right-5"></i>Please enter an email</span>
                            <span
                                ng-show="emailForm.email.$error.email && !emailForm.email.$pristine && !emailForm.email.$error.required"
                                class="validation invalid"><i
                                    class="icon-remove padding-right-5"></i>Enter a valid email id. </span>
                                </div>
                                <div class="alert alert-error margin-top-20 forgot-password" ng-show="errorEmail">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    {{emailErrorMessage}}
                                </div>
                                <div class="alert alert-success margin-top-20 forgot-password" ng-show="successEmail">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    {{emailSuccessMessage}}
                                </div>
                                <div class="control-group">
                                    <button type="submit" ng-disabled="emailForm.$invalid" ng-click="sendByEmail()"
                                            class="btn">Reset
                                        Password
                                    </button>
                                    <button type="reset" class="btn">Cancel</button>

                                </div>

                            </form>

                        </div>
                    </div>
                    <!-- / Tabs content -->
                </div>
            </div>
        </div>

    </div>

</section>