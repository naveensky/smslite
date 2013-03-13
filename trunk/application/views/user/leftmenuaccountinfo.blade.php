<!--All links for left hand side menus-->
<?php $leftMenu = array(
    '/user/profile' => 'Edit Profile',
    '/user/update_password' => 'Update Password',
    '/user/transaction_history' => 'Transaction History');
?>

<ul class="nav nav-list">
    <li class="nav-header">Account</li>
    @foreach($leftMenu as $key => $value)
    <li class="<% (strpos(URL::current(), $key)) ? "active" : "" %>"><a href='<% "#$key" %>'><% $value %></a></li>
    @endforeach
</ul>
