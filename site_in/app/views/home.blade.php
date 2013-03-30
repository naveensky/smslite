@extends('layouts.common')

@section('content')

<div class="row">
    <div class="span12">
        <div id="logo">
            <img src="img/logo.png">
            <small>Next Generation Communication</small>
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
                Assertively syndicate leveraged web services via enterprise-wide process improvements. Synergistically
                transition world-class methods of empowerment with functionalized convergence.
            </p>

            <p>
                Compellingly pursue visionary e-business whereas resource sucking interfaces. Objectively evolve 24/365
                services rather than principle-centered potentialities.
            </p>
        </div>
    </div>
</div>

<div class="row">
    <div class="span12">
        <div class="punchline top-gap">
            This is the punchline area
            <a href="#" class="pull-right btn btn-large btn-primary">Know More</a>
        </div>
    </div>
</div>

<div class="row clients top-gap">
    <div class="span12">
        <h3>Happy Clients</h3>
    </div>
    <div class="span3"><img src="<% URL::asset('img/presidium.png') %>" alt="Presidium School, Delhi"></div>
    <div class="span3"><img src="<% URL::asset('img/holy-innocent.png') %>" alt="Holy Innocents Public School, Delhi"></div>
    <div class="span3"><img src="<% URL::asset('img/ramjas.png') %>" alt="Ramjas School, Delhi"></div>
    <div class="span3"><img src="<% URL::asset('img/sms.png') %>" alt="St. Marks, Delhi"></div>
</div>

@stop
