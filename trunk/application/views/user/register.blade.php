<section class="container" style="margin-top:20px;">
    <div class="box">
        <!--<div class="tabbable">-->

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

                <form class="form-horizontal offset2">
                    <div class="control-group">
                        <label class="control-label" for="inputMobile">Mobile</label>

                        <div class="controls">
                            <input type="text" id="inputMobile" ng-model="user.mobile" placeholder="Mobile"> <span
                                class="validation valid"><i
                                class="icon-ok padding-right-5"></i>This number is valid.</span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="inputEmail">Email</label>

                        <div class="controls">
                            <input type="text" id="inputEmail" ng-model="user.email" placeholder="Email">
                    <span class="validation invalid"><i
                            class="icon-remove padding-right-5"></i> Enter valid email-id.</span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="inputPassword">Password</label>

                        <div class="controls">
                            <input type="password" id="inputPassword" ng-model="user.password" placeholder="Password">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="inputPassword">Confirm Password</label>

                        <div class="controls">
                            <input type="password" id="inputPassword" ng-model="user.confirmPassword"
                                   placeholder="Confirm Password">
                        </div>
                    </div>
                    <div class="controls margin-top-20">
                        <label class="checkbox">
                            <input type="checkbox"> I agree
                        </label>
                        <button type="button" ng-click="registerUser()" class="btn btn-success">Next</button>
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