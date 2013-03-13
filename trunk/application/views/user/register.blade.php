<section class="container" style="margin-top:20px;">
    <div class="box">
        <!-- Tabs
       ================================================== -->
        <ul class="nav nav-tabs" id="register-tabs">
            <li class="active"><a href="#">Sign up</a></li>
            <li><a href="#">School Information</a></li>
            <li><a href="#">Verify Mobile</a></li>
            <li><a href="#">Verify Email</a></li>
            <li><a href="#">Welcome</a></li>
        </ul>
        <!-- / Tabs -->

        <!-- Tabs content
       ================================================== -->
        <div class="tab-content">

            <!-- Recent comments tab content -->
            <div class="tab-pane fade in active" id="sign-up">
                <div class="alert alert-error margin-top-20 user-register" ng-show="emailUsed">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    {{emailUsedMessage}}
                </div>
                <form name="form" novalidate class="form-horizontal offset2">
                    <div class="control-group">
                        <label class="control-label" for="inputMobile">Mobile</label>

                        <div class="controls">
                            <input type="text" id="inputMobile" name="mobile" ng-model="mobile" ng-minLength="8"
                                   ng-pattern="/^\+{0,1}\d+$/" ng-required="true" placeholder="Mobile">
                            <span ng-show="form.mobile.$error.required && !form.mobile.$pristine "
                                  class="validation invalid"><i class="icon-remove padding-right-5"></i>Please enter your mobile number</span>
                            <span ng-show="form.mobile.$invalid && !form.mobile.$pristine && !form.mobile.$error.required" class="validation invalid"><i
                                    class="icon-remove padding-right-5"></i>The mobile number must be at least 8 digits</span>
                                                        <span ng-show="form.mobile.$valid && !form.mobile.$pristine"
                                                              class="validation valid"><i
                                                                class="icon-ok padding-right-5"></i></span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="inputEmail">Email</label>

                        <div class="controls">
                            <input type="email" id="inputEmail" ng-required="true" name="email" ng-model="email"
                                   placeholder="Email">
                            <span ng-show="form.email.$error.required && !form.email.$pristine "
                                  class="validation invalid"><i class="icon-remove padding-right-5"></i>Please enter an email</span>
                            <span ng-show="form.email.$error.email && !form.email.$pristine && !form.email.$error.required" class="validation invalid"><i
                                    class="icon-remove padding-right-5"></i>Enter a valid email id. </span>
                            <span ng-show="form.email.$valid && !form.email.$pristine" class="validation valid"><i
                                    class="icon-ok padding-right-5"></i></span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="inputPassword">Password</label>

                        <div class="controls">
                            <input type="password" id="inputPassword"name="password" ng-required="true" ng-change="changePassword()" ng-model="password" placeholder="Password">
                            <span class="validation valid" ng-show='form.password.$valid'>
                            <i class='icon-ok padding-right-5'></i></span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="inputPassword">Confirm Password</label>
                        <div class="controls">
                            <input type="password" id="rePassword" ng-model="rePassword" ng-required="true" name="rePassword" same-as="password"
                                   placeholder="Confirm Password">
                            <span ng-show="form.rePassword.$error.required && !form.rePassword.$pristine "
                                  class="validation invalid"><i class="icon-remove padding-right-5"></i>Please confirm password</span>
                            <span ng-show="form.rePassword.$invalid && !form.rePassword.$pristine && !form.rePassword.$error.required"
                                  class="validation invalid"><i class="icon-remove padding-right-5"></i>Password Not Matched</span>
                            <span ng-show="form.rePassword.$valid && !form.rePassword.$pristine" class="validation valid"><i
                                    class="icon-ok padding-right-5"></i></span>
                        </div>
                    </div>
                    <div class="controls margin-top-20">
                        <label class="checkbox">
                            <input type="checkbox" ng-model="iAgree">I agree to abide by the <a href="">Terms & Conditions</a>
                             <span ng-show="!iAgree"
                                   class="validation invalid"><br/><i class="icon-remove padding-right-5"></i>In order to use our service you must agree to our terms and conditions</span>
                        </label>

                        <button type="button" ng-click="register()" ng-disabled="form.$invalid || !iAgree" class="btn btn-success">
                            Next
                        </button>
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