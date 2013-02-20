<section class="container" style="margin-top:20px;">
    <div class="box">
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

                <form class="form-horizontal offset2">
                    <div class="control-group">
                        <label class="control-label" for="inputVerificationCode">Enter Verification Code</label>

                        <div class="controls">
                            <input type="text" id="inputVerificationCode" placeholder="Enter Verification Code">
                        </div>
                        <br>

                        <div class="controls">
                            <button type="submit" class="btn">Verify</button>
                            <button type="submit" class="btn">Send Again</button>
                        </div>

                    </div>

                </form>
                <hr/>
                <form class="form-horizontal offset2">

                    <h3><i class="icon-mobile-phone icon-large padding-right-5"></i>Need to update Mobile</h3>

                    <div class="control-group">
                        <label class="control-label" for="inputUpdateMobile">Update Mobile</label>

                        <div class="controls">
                            <input type="text" id="inputUpdateMobile" placeholder="Update Mobile">
                            <button type="submit" class="btn">Update</button>

                        </div>

                        <div class="controls margin-top-20">

                            <button type="button" class="btn btn-success" ng-click="verifyMobile()">Next</button>
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


