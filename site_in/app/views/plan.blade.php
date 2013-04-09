@extends('layouts.common')

@section('content')

<div class="row">
    <div class="span12 page-title">
        <h2>Buy Plan</h2>
        <hr>
    </div>

</div>

<div class="row">
    <div class="span4">
        <div class="page-main-icon page-buy-icon">
            <i class="icon-shopping-cart"></i>
        </div>
    </div>
    <div class="span8">
        <p>
            Please fill the following form with your correct details and our Sales Superstars will get in touch with you
            to sign you up for your MSNGR account.
        </p>

        <script type="text/javascript" src="http://saberforms.com/assets/js/3822f66936"></script>
        <form class="form-horizontal" id="form_3822f66936" method="post"
              action="http://saberforms.com/form/submit/3822f66936">
            <div class="row">
                <div class="span4"><label for="name">Name*</label>
                    <input type="text" class=" required input-xlarge" name="name" id="sf_element_name"/></div>
                <div class="span4">
                    <label for="phone">Phone*</label>
                    <input type="text" class=" required digits input-xlarge" name="phone" id="sf_element_phone"/></div>

            </div>
            <div class="row">
                <div class="span4"><label for="plan">Plan</label>
                    <select name="plan" id="plan" class="input-xlarge">

                        <?php foreach($plans as $pl): ?>
                        <option value="<% $pl["credits"] %>|<% $pl["price"] %>"

                             <?php if($pl['id']==$plan['id']): ?>
                            selected="selected"
                            <?php endif; ?>
                        >
                            <% $pl["credits"] %> sms at Rs. <% $pl["price"] %>

                        </option>
                        <?php endforeach; ?>
                    </select>

                </div>
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