<?php $leftMenu = array(
    '/teacher/list' => 'List',
    '/teacher/upload' => 'Upload',
    '/teacher/export' => 'Export',
    '/teacher/help' => 'Help');

?>
<ul class="nav nav-list">
    <li class="nav-header">Teachers</li>
    <?php foreach ($leftMenu as $key => $value) { ?>
    <li class="<?php if (strpos(URL::current(), $key)) echo "active";?>"><a href="#<?php echo $key;?>"><%$value%></a></li>
    <?php } ?>
</ul>

