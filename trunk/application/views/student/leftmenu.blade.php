<!--All links for left hand side menus-->
<?php $leftMenu = array(
    '/student/list' => 'List',
    '/student/upload' => 'Upload',
    '/student/export' => 'Export',
    '/student/help' => 'Help');
?>

<ul class="nav nav-list">
    <li class="nav-header">Students</li>
    @foreach($leftMenu as $key => $value)
    <li class="<% (strpos(URL::current(), $key)) ? "active" : "" %>"><a href='<% "#$key" %>'><% $value %></a></li>
    @endforeach
</ul>
