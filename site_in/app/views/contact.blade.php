@extends('layouts.common')

@section('content')

<div class="row">
    <div class="span12 page-title">
        <h2>Contact Us</h2>
        <hr>
    </div>

</div>

<div class="row">
    <div class="span4">
        <div class="page-main-icon page-contact-icon">
            <i class="icon-map-marker"></i>
        </div>
    </div>
    <div class="span8">
        <p>If you want to know more about how MSNGR can help you better or how to purchase a MSNGR subscription or have any query, please fill the following
            form and our Support Superstars will get in touch with you.</p>

        <script type="text/javascript" src="http://saberforms.com/assets/js/avvbnz6lvl"></script>
        <form class="form-horizontal" id="form_avvbnz6lvl" method="post"
              action="http://saberforms.com/form/submit/avvbnz6lvl">
            <div class="row">
                <div class="span4"><label for="name">Name*</label>
                    <input type="text" class=" required input-xlarge" name="name" id="sf_element_name"/></div>
                <div class="span4">
                    <label for="phone">Phone*</label>
                    <input type="text" class=" required digits input-xlarge" name="phone" id="sf_element_phone"/></div>

            </div>
            <div class="row">
                <div class="span4"><label for="email">Email</label>
                    <input type="text" class=" email input-xlarge" name="email" id="sf_element_email"/></div>
                <div class="span4"><label for="school">School*</label>
                    <input type="text" class=" required input-xlarge" name="school" id="sf_element_school"></div>
            </div>
            <div class="row"><br>
                <div class="span4"><input type="submit" value="Submit Query" class="btn btn-large btn-success"></div>
            </div>


        </form>
    </div>
</div>

@stop
