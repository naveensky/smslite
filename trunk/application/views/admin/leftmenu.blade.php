<!--All links for left hand side menus-->
<?php $leftMenu = array(
    '/admin/allocate_credits' => 'Allocate Credits'
);
?>
<ul class="nav nav-list">
    <li class="nav-header">Admin</li>
    @foreach($leftMenu as $key => $value)
    <li class="<% (strpos(URL::current(), $key)) ? "active" : "" %>"><a href='<% "#$key" %>'><% $value %></a></li>
    @endforeach
</ul>
