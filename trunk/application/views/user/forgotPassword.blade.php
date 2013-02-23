<section class="container" style="margin-top:20px;">
    <div class="row">
        <div class="span3">
            <div class="box" style="padding: 8px 0;">
                <ul class="nav nav-list">
                    <li class="nav-header">List header</li>
                    <li class="active"><a href="#">Home</a></li>
                    <li><a href="#">Library</a></li>
                    <li><a href="#">Applications</a></li>
                    <li class="nav-header">Another list header</li>
                    <li><a href="#">Profile</a></li>
                    <li><a href="#">Settings</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Help</a></li>
                </ul>
            </div>

        </div>

        <div class="span9">
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

                            <form class="form-horizontal">

                                <div class="control-group">
                                    <label>Mobile Number</label>
                                    <input type="text" ng-model="mobileNumber" placeholder="Mobile Number">
                                        <span class="validation text-success">
                                        <i class="icon-ok padding-right-5"></i>This number is valid.</span>
                                </div>
                                <div class="control-group">
                                    <button type="submit" class="btn">Reset Password</button>
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
                                    <input type="email" ng-model="email" name="email" ng-required="true" placeholder="Email ID">
<span ng-show="emailForm.email.$error.required && !emailForm.email.$pristine "
      class="validation invalid"><i class="icon-remove padding-right-5"></i>Please enter an email</span>
                            <span ng-show="emailForm.email.$error.email && !emailForm.email.$pristine && !emailForm.email.$error.required"
                                  class="validation invalid"><i
                                    class="icon-remove padding-right-5"></i>Enter a valid email id. </span>
                            <span ng-show="emailForm.email.$valid && !emailForm.email.$pristine"
                                  class="validation valid"><i
                                    class="icon-ok padding-right-5"></i></span>
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
                                    <button type="submit" ng-disabled="emailForm.$invalid" ng-click="sendByEmail()" class="btn">Reset
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