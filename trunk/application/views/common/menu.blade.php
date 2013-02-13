@if(Auth::check())
<!--Menu Placeholder -->
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="brand" href="#">SMSLite</a>

            <div class="nav-collapse collapse">
                <ul class="nav">
                    <li><a href="#/student"><i class="icon-group"></i> Students</a></li>
                    <li><a href="#/student"><i class="icon-user-md"></i> Teachers</a></li>
                    <li><a href="#/student"><i class="icon-comments"></i> SMS</a></li>
                    <li><a href="#/student"><i class="icon-cog"></i> Account</a></li>
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
            <a class="brand" href="#">SMSLite</a>

            <div class="nav-collapse collapse">
                <ul class="nav pull-right">
                    <li><a href="#/user/register"><i class="icon-user"></i>Create an Account</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endif
