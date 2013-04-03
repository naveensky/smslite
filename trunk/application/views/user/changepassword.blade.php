<section class="container">
    <div class="row">
        <div class="span12">

            <div class="box">

                <h3 class="margin-top-0"><i class="icon-unlock icon-large padding-right-5"></i>Change
                    Password</h3>

                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum
                    has been
                    the industry's standard dummy text ever since the 1500s, when an unknown printer took a
                    galley
                    of type and scrambled it to make a type specimen book.</p>

                <form name="form" class="form-horizontal">
                    <div class="alert alert-error margin-top-20 forgot-password" ng-show="errorChangePassword">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        {{errorChangeMessage}}
                    </div>
                    <div class="control-group">
                        <label>New Password</label>
                        <input type="password" id="inputPassword" name="password" ng-required="true"
                               ng-change="changePassword()" ng-model="password"
                               placeholder="Password">
                        <span ng-show="form.password.$error.required && !form.password.$pristine"
                              class="validation invalid"><i class="icon-remove padding-right-5"></i>Please enter password</span>

                    </div>

                    <div class="control-group">
                        <label>Confirm New Password</label>
                        <input type="password" id="inputConfirmPassword" ng-required="true" ng-model="rePassword"
                               name="rePassword" same-as="password" placeholder="Password">
                        <span ng-show="form.rePassword.$error.required && !form.rePassword.$pristine "
                              class="validation invalid"><i class="icon-remove padding-right-5"></i>Please confirm password</span>
                        <span
                            ng-show="form.rePassword.$invalid && !form.rePassword.$pristine && !form.rePassword.$error.required"
                            class="validation invalid"><i
                                class="icon-remove padding-right-5"></i>Password Not Matched</span>
                    </div>
                    <div class="control-group">
                        <input type="hidden" ng-init="x_token='<?php echo $email; ?>'">
                        <button type="submit" ng-disabled="form.$invalid" ng-click="resetPassword()"
                                class="btn btn-green">Change Password
                        </button>
                        <button type="submit" class="btn">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
</section>