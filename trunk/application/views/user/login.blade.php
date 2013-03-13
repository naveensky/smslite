<div class="span4 offset3 well" ng-controller="User_Login">
    <form name="form">
        <legend><i class="icon-lock"></i> Sign In</legend>
        @if ( Session::has('password_change_success'))
        <div class="alert alert-success margin-top-20">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <?php echo Session::get('password_change_success');?>
        </div>
        @endif
        <div ng-show="showError" class="alert alert-error">
            <i class="icon-remove"></i> Invalid email or password, Try again.
        </div>

        <div class="control-group">
            <label for="email">Email</label>
            <input tabindex="1" class="span4" ng-required="true" name="email" id="email" ng-model="user.email"
                   type="email">
            <span ng-show="form.email.$error.required && !form.email.$pristine " class="text-error">Please enter an email</span>
            <span ng-show="form.email.$error.email && !form.email.$pristine "
                  class="text-error">Enter a valid email id. </span>
        </div>
        <div class="control-group">
            <label for="password">Password <a class="pull-right" href="#/user/forgot-password">Forgot
                    Password?</a></label>
            <input tabindex="2" type="password" ng-required="true" name="password" class="span4" id="password"
                   ng-model="user.password">
            <span ng-show="form.password.$error.required && !form.password.$pristine" class="text-error">Please enter a password</span>
        </div>
        <div class="control-group">
            <button tabindex="3" ng-click="login(user)" ng-disabled="form.$invalid" class="btn btn-block btn-info"><i
                    class="icon-signin"></i> Log In
            </button>
        </div>
        <div class="control-group">
            <a href="#/user/register">Sign up for a free account</a>
        </div>
    </form>
</div>