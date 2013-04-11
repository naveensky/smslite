@extends('layouts.common')

@section('meta')
<title>Stay Connected with Parents with MSNGR</title>
<meta name="description" content="Use MSNGR to
                easily communicate with the parents
                by text messages(sms). Share latest updates and alerts with the parents with the click of a button.">
<meta name="keywords"
      content="school sms solutions, send sms to parents, school sms software, sms software, connect with parents, sms system for schools, school notification system">

@stop

@section('content')

<div class="row">
    <div class="span12">
        <div id="logo">
            <img src="img/logo.png">
            <small>Stay connected with Parents</small>
        </div>
    </div>
</div>


<!--Video for Home Page-->

<!--<div class="row">-->
<!--    <div class="span8 offset2">-->
<!--        <div id="introductory-video">-->
<!--            <iframe src="http://player.vimeo.com/video/1935228?title=0&amp;byline=0&amp;portrait=0" width="100%"-->
<!--                    height="400px"-->
<!--                    frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>-->
<!---->
<!--        </div>-->
<!--        <div class="video-shadow">-->
<!--            <img src="img/shadow.png">-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->

<div class="row">
    <div class="span8 offset2">
        <div class="intro-text">
            <p>
                Information is everything. The parents of your students want to stay informed about everything. They
                want to know how their child is performing, they want to know when the next PTA meeting is going to
                happen, when the fees are due or when the school is off.
            </p>

            <p>
                Gone are the days of paper circulars and diary notes. This is the time of instant alerts. Use MSNGR to
                easily communicate with the parents
                by text messages(sms). Share latest updates and alerts with the parents with the click of a button. <br>
                <a
                    href="/features">See Features</a>
            </p>
        </div>
    </div>
</div>


<div class="row clients top-gap">
    <div class="span12">
        <h3>Our Happy Clients</h3>
    </div>
    <div class="span3"><img src="<% URL::asset('img/presidium.png') %>" alt="Presidium School, Delhi"
                            title="Presidium School, Delhi"></div>
    <div class="span3"><img src="<% URL::asset('img/holy-innocent.png') %>" alt="Holy Innocents Public School, Delhi"
                            title="Holy Innocents Public School, Delhi">
    </div>
    <div class="span3"><img src="<% URL::asset('img/ramjas.png') %>" alt="Ramjas School, Delhi"
                            title="Ramjas School, Delhi"></div>
    <div class="span3"><img src="<% URL::asset('img/sms.png') %>" alt="St. Marks, Delhi" title="St. Marks, Delhi"></div>
</div>

<div class="row">
    <div class="span12">
        <div class="punchline top-gap">
            Try this service for free with our complimentary 100 credits plan
            <a href="/pricing" class="pull-right btn btn-large btn-primary">Sign Up</a>
        </div>
    </div>
</div>


@stop
