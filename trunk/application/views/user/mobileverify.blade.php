<section class="container" style="margin-top:20px;">
    <div class="box" ng-controller="User_Register">
        <!--<div class="tabbable">-->

        <!-- Tabs
       ================================================== -->
        <ul class="nav nav-tabs" id="register-tabs">
            <li><a href="#">Sign up</a></li>
            <li><a href="#">School Information</a></li>
            <li class="active"><a href="#">Verify Mobile</a></li>
            <li><a href="#">Verify Email</a></li>
            <li><a href="#">Welcome</a></li>
        </ul>
        <!-- / Tabs -->

        <!-- Tabs content
       ================================================== -->
        <div class="tab-content">

            <!-- Recent comments tab content -->
            <div class="tab-pane fade in active" id="sign-up">

                <form name="mobileVerifyForm" novalidate class="form-horizontal offset2">
                    <div class="control-group">
                        <label class="control-label" for="inputVerificationCode">Enter Verification Code</label>

                        <div class="controls">
                            <input type="text" id="inputVerificationCode" name="mobileCode" ng-required="true"
                                   ng-model="mobileVerificationCode"
                                   placeholder="Enter Verification Code">
                            <span
                                ng-show="mobileVerifyForm.mobileCode.$error.required && !mobileVerifyForm.mobileCode.$pristine "
                                class="validation invalid"><i class="icon-remove padding-right-5"></i>Please enter code we have sent to your mobile</span>
                        </div>
                        <br>

                        <div class="controls">
                            <button type="submit" ng-click="verifyMobile()" ng-disabled="mobileVerifyForm.$invalid"
                                    class="btn">Verify
                            </button>
                            <button type="submit" ng-click="sendMobileCodeAgain()" class="btn">Send Again</button>
                        </div>
                    </div>
                    <div class="alert alert-success smsresend" ng-show="SMSResent">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        New mobile verification code sent.
                    </div>
                    <div class="alert alert-error smsresend" ng-show="SMSResentError">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        Please enter correct mobile verification code.
                    </div>
                </form>
                <hr/>
                <form name="mobileUpdateForm" novalidate class="form-horizontal offset2">

                    <h3><i class="icon-mobile-phone icon-large padding-right-5"></i>Need to update Mobile</h3>

                    <div class="control-group">
                        <label class="control-label" for="inputUpdateMobile">Update Mobile</label>

                        <div class="controls">
                            <input type="text" id="inputUpdateMobile" ng-model="newMobileNumber" ng-minLength="8"
                                   ng-required="true" ng-pattern="/^\+{0,1}\d+$/"
                                   name="newNumber"
                                   placeholder="Update Mobile">
                            <span ng-show="mobileUpdateForm.newNumber.$valid && !mobileUpdateForm.newNumber.$pristine"
                                  class="validation valid"><i
                                    class="icon-ok padding-right-5"></i></span>
                            <button type="submit" ng-click="updateMobile()" ng-disabled="mobileUpdateForm.$invalid"
                                    class="btn">Update
                            </button>

                            <div
                                ng-show="mobileUpdateForm.newNumber.$error.required && !mobileUpdateForm.newNumber.$pristine "
                                class="validation invalid"><i class="icon-remove padding-right-5"></i>Please enter your
                                mobile number
                            </div>
                            <div
                                ng-show="mobileUpdateForm.newNumber.$invalid && !mobileUpdateForm.newNumber.$pristine && !mobileUpdateForm.newNumber.$error.required"
                                class="validation invalid"><i
                                    class="icon-remove padding-right-5"></i>The mobile number must be at least 8 digits
                            </div>

                        </div>

                        <div class="alert alert-success margin-top-20 mobile-update" ng-show="IsMobileUpdated">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            Your mobile number is updated and mobile verification code has been sent to you.
                        </div>
                        <div class="alert alert-success margin-top-20 mobile-update" ng-show="IsMobileUpdatedError">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            Sorry Mobile Number is not Updated Please try again.
                        </div>

                    </div>
                </form>
            </div>
        </div>
        <!-- / Tabs content -->
    </div>
    <!--</div>-->
</section>

<script>
    $(function () {
        $('#register-tabs a').click(function (e) {
            e.preventDefault();
        });
    })
</script>


