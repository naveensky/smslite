<div class="row">
    <div class="span3">
        <div class="box" style="padding: 8px 0;">
            @render('user.leftmenuaccountinfo')
        </div>
    </div>

    <div class="span9">
        <div class="box">
            <h3><i class="icon-user icon-large"> Update Password </i></h3>

            <div class="row">
                <div class="span8">
                    <form name="form" novalidate>
                        <div class="control-group">
                            <label>Old Password</label>
                            <input type="password" id="oldPassword" name="oldPassword" ng-required="true"
                                   ng-model="oldPassword"
                                   placeholder="Old Password">
                        <span ng-show="form.oldPassword.$error.required && !form.oldPassword.$pristine"
                              class="validation invalid"><i class="icon-remove padding-right-5"></i>Please enter your old password</span>
                        </div>
                        <div class="control-group">
                            <label>New Password</label>
                            <input type="password" id="inputPassword" name="password" ng-required="true"
                                   ng-change="changePassword()" ng-model="newPassword"
                                   placeholder="New Password">
                        <span ng-show="form.password.$error.required && !form.password.$pristine"
                              class="validation invalid"><i class="icon-remove padding-right-5"></i>Please enter new password</span>
                        </div>
                        <div class="control-group">
                            <label>Confirm New Password</label>
                            <input type="password" id="inputConfirmPassword" ng-required="true" ng-model="rePassword"
                                   name="rePassword" same-as="newPassword" placeholder="Password">
                        <span ng-show="form.rePassword.$error.required && !form.rePassword.$pristine "
                              class="validation invalid"><i class="icon-remove padding-right-5"></i>Please confirm password</span>
                        <span
                            ng-show="form.rePassword.$invalid && !form.rePassword.$pristine && !form.rePassword.$error.required"
                            class="validation invalid"><i
                                class="icon-remove padding-right-5"></i>Password Not Matched</span>
                            <span ng-show="form.rePassword.$valid && !form.rePassword.$pristine"
                                  class="validation valid"><i
                                    class="icon-ok padding-right-5"></i></span>
                        </div>
                        <div class="control-group">
                            <div class="alert alert-success margin-top-20" style="width:35%"
                                 ng-show="successUpdatePassword">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                {{message}}
                            </div>
                            <div class="alert alert-error margin-top-20" style="width:45%"
                                 ng-show="errorUpdatePassword">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                {{message}}
                            </div>
                            <button type="submit" ng-disabled="form.$invalid" ng-click="updatePassword()"
                                    class="btn btn-green">Change Password
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

