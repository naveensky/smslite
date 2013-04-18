<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @yield('meta')
    <meta name="author" content="Green Apple Solutions">

    <!-- Le styles -->
    <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.no-icons.min.css"
          rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.0.2/css/font-awesome.css" rel="stylesheet">
    <link href="<% URL::asset('css/flat-ui.css') %>" rel="stylesheet">
    <link href="<% URL::asset('css/app.css') %>" rel="stylesheet">
    <title>Stay Connected with Parents</title>
    <!--[if lt IE 7]>
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.0.2/css/font-awesome-ie7.css" rel="stylesheet">
    <![endif]-->

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6.2/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
    <link rel="shortcut icon" href=""<% URL::asset('img/favicon.png') %>">

    <meta property="og:type" content="website" />
    <meta property="og:title" content="MSNGR" />
    <meta property="og:description" content="Stay connected with Parents" />
    <meta property="og:url" content="http://msngr.in/" />
    <meta property="og:image" content="<% URL::asset('img/logo_icon_large.png') %>" />
    <meta property="fb:admins" content="534000562"/>

</head>

<body>

<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="brand" href="/"><img src="<% URL::asset('img/logo_icon_large.png') %>"/> </a>

            <ul class="nav">

                <li><a href="/features">Features</a></li>
                <li><a href="/pricing">Pricing</a></li>
                <li><a target="_blank" href="http://app.msngr.in">Login / Signup</a></li>
                <li><a href="/contact">Contact</a></li>
            </ul>
            <ul class="nav contact-info pull-right">
                <li><a href="mailto:info@msngr.in">info@msngr.in</a></li>
                <li><a href="#">+91 - 995 322 2492 </a></li>
            </ul>
        </div>
    </div>
</div>

<div class="container">
    @yield('content')
</div>


<div class="footer palette palette-clouds">
    <div class="container">
        <div class="row">
            <div class="span4">&copy <a href="/">MSNGR.IN</a>
                | <a href="/terms">Terms of Use</a>
                | <a href="/privacy">Privacy Policy</a>
            </div>
            <div class="span4" style="text-align: center"><span class="center">Stay connected with Parents</span></div>
            <div class="span4"><span class="pull-right"> made with ❤ at <a target="_blank"
                        href="http://www.greenapplesolutions.com">green apple solutions</a></span></div>
        </div>
    </div>
</div>


<!--Google Analytics-->

<script type="text/javascript">
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-16774060-27']);
    _gaq.push(['_setDomainName', 'msngr.in']);
    _gaq.push(['_trackPageview']);

    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();

</script>

<!-- Le javascript
================================================== -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="<% URL::asset('js/bootstrap.min.js') %>"></script>
<script src="<% URL::asset('js/app.js') %>"></script>

</body>
</html>
