<section class="container" style="margin-top:20px;">
    <div class="box">
        <!--<div class="tabbable">-->

        <!-- Tabs
       ================================================== -->
        <ul class="nav nav-tabs" id="register-tabs">
            <li><a href="#">Sign up</a></li>
            <li><a href="#">School Information</a></li>
            <li><a href="#">Verify Mobile</a></li>
            <li class="active"><a href="#">Verify Email</a></li>
            <li><a href="#">Welcome</a></li>
        </ul>
        <!-- / Tabs -->

        <!-- Tabs content
       ================================================== -->
        <div class="tab-content">

            <!-- Recent comments tab content -->
            <div class="tab-pane fade in active" id="sign-up">

                <form class="form-horizontal">
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been
                        the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley
                        of type and scrambled it to make a type specimen book.</p>

                    <div class="controls margin-top-20 margin-left-0">
                        <button type="button" class="btn" ng-click="sendEmailAgain()">Send Email Again</button>
                        <div class="alert alert-success margin-top-20" style="width:35%" ng-show="emailResent">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            Email with further instruction is sent to you
                        </div>
                    </div>

                </form>
                <hr>

                <form name="form" novalidate class="form-horizontal">
                    <h3><i class="icon-envelope-alt icon-large padding-right-5"></i>Need to update Email</h3>

                    <div class="control-group">
                        <label class="control-label" for="inputUpdateEmail">Update Email</label>

                        <div class="controls">
                            <input type="email" id="inputUpdateEmail" ng-required="true" name="email"
                                   ng-model="newEmail" placeholder="Update Email">
                            <button type="submit" class="btn" ng-click="updateEmail()" ng-disabled="form.$invalid">
                                Update
                            </button>
                            <div ng-show="form.email.$error.required && !form.email.$pristine "
                                 class="validation invalid"><i class="icon-remove padding-right-5"></i>Please enter an
                                email
                            </div>
                            <div
                                ng-show="form.email.$error.email && !form.email.$pristine && !form.email.$error.required"
                                class="validation invalid"><i
                                    class="icon-remove padding-right-5"></i>Enter a valid email id.
                            </div>
                            <div class="alert alert-success margin-top-20" style="width:35%" ng-show="resetEmail">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                Email id is updated successfully
                            </div>
                        </div>
                        <div class="controls margin-top-20">
                            <button type="button" class="btn btn-success" ng-click="skipEmailVerify()">Skip</button>
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