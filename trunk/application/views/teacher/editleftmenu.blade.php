<!--All links for left hand side menus-->
<?php $leftMenu = array(
    '/teacher/list' => 'List',
    '/teacher/upload' => 'Upload',
    '/teacher/edit' => 'Edit'
);

?>
<ul class="nav nav-list">
    <li class="nav-header">Teacher</li>
    @foreach($leftMenu as $key => $value)
    <li class="<% (strpos(URL::current(), $key)) ? "active" : "" %>"><a href='<% "#$key" %>'><% $value %></a></li>
    @endforeach
</ul>

