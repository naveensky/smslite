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


<div class="row">
    <div class="span10 offset1">
        <div id="introductory-video">
            <iframe src="http://player.vimeo.com/video/1935228?title=0&amp;byline=0&amp;portrait=0" width="100%"
                    height="500px"
                    frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>

        </div>
        <div class="video-shadow">
            <img src="img/shadow.png">
        </div>
    </div>
</div>

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
    <div class="span3"><img src="http://placehold.it/270x210"></div>
    <div class="span3"><img src="http://placehold.it/270x210"></div>
    <div class="span3"><img src="http://placehold.it/270x210"></div>
    <div class="span3"><img src="http://placehold.it/270x210"></div>
</div>

@stop
