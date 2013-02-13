<!DOCTYPE html>
<html lang="en" ng-app="app">
<head>
    <meta charset="utf-8">
    <title>TBD</title>
    <meta name="author" content="http://greenapplesolutions.com/">
    <!--styles-->
    <% Asset::styles() %>

    <!--[if IE 7]>
    <% HTML::style('css/font-awesome-ie7.min.css') %>
    <![endif]-->

</head>
<body>
@render('common.menu')

<div class="container" ng-view>
    <!--Angular View Placeholder-->
</div>

<!--scripts-->
<% Asset::scripts() %>

</body>
</html>


