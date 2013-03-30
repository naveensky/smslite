<!DOCTYPE html>
<html lang="en" ng-app="app">
<head>
    <meta charset="utf-8">
    <title>MSNGR.IN</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="http://greenapplesolutions.com/">
    <link rel="shortcut icon" href='img/favicon.png'>
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

<div id="ajax-loader">
    Loading ...
</div>

<!--scripts-->
<% Asset::scripts() %>


@if (Request::is_env("prod"))

<script type="text/javascript">
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-16774060-27']);
    _gaq.push(['_setDomainName', 'app.msngr.in']);
    _gaq.push(['_trackPageview']);

    (function () {
        var ga = document.createElement('script');
        ga.type = 'text/javascript';
        ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(ga, s);
    })();

    //track ajax requests
    $(document).on('ajaxComplete', function (event, request, settings) {
        _gaq.push(['_trackPageview', settings.url]);
    });

</script>

@endif

</body>
</html>


