@if(Auth::check())
<!--Menu Placeholder -->
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="brand" href="#">  <%  HTML::image('img/logo.png', "msngr.in logo") %></a>

            <div class="nav-collapse collapse">
                <ul class="nav">
                    <li><a href="#/student/list"><i class="icon-group"></i> Students</a></li>
                    <li><a href="#/teacher/list"><i class="icon-user"></i> Teachers</a></li>
                    <li><a href="#/sms"><i class="icon-comments"></i> SMS</a></li>
                    <li><a href="#/report/sms"><i class="icon-bar-chart"></i> Report</a></li>
                    <li><a href="#/user/profile"><i class="icon-cog"></i> Account</a></li>
                    @if(Util::is_in_role(Role::USER_ROLE_SUPER_ADMIN))
                    <li><a href="#/admin/allocate_credits"><i class="icon-lock"></i> Admin</a></li>
                    @endif;
                </ul>
                <ul class="nav pull-right">
                    <li><a href="/user/logout"><i class="icon-signout"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
@else
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="brand" href="#">  <%  HTML::image('img/logo.png', "msngr.in logo") %></a>

            <div class="nav-collapse collapse">
                <ul class="nav pull-right">
                    <li><a href="#/user/register"><i class="icon-user"></i>Create an Account</a></li>
                    <li><a href="#/user/login"><i class="icon-signin"></i>Sign In</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endif
