@extends('layouts.common')

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
                happen, when the fees are due and when the school is off.
            </p>

            <p>
                Gone are the days of paper circulars and diary notes. This is the time of instant alerts. Use MSNGR to easily communicate with the parents
                by text messages(sms). Share latest updates and alerts with the parents with the click of a button. <br> <a
                href="">Know more</a>
            </p>
        </div>
    </div>
</div>

<div class="row">
    <div class="span12">
        <div class="punchline top-gap">
            Try and evaluate this service for free with our complimentary 100 credits plan
            <a href="#" class="pull-right btn btn-large btn-primary">Sign Up</a>
        </div>
    </div>
</div>

<div class="row clients top-gap">
    <div class="span12">
        <h3>Happy Clients</h3>
    </div>
    <div class="span3"><img src="<% URL::asset('img/presidium.png') %>" alt="Presidium School, Delhi"></div>
    <div class="span3"><img src="<% URL::asset('img/holy-innocent.png') %>" alt="Holy Innocents Public School, Delhi">
    </div>
    <div class="span3"><img src="<% URL::asset('img/ramjas.png') %>" alt="Ramjas School, Delhi"></div>
    <div class="span3"><img src="<% URL::asset('img/sms.png') %>" alt="St. Marks, Delhi"></div>
</div>

@stop
