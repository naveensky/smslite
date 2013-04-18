@extends('layouts.common')

@section('meta')
<title>Our Plans - MSNGR</title>
<meta name="description"
      content="MSNGR.IN offers multiple plans to fit your needs. You can buy the plan that fulfills your requirements without having to buy limited or excess credits.">
<meta name="keywords"
      content="">

@stop
@section('content')

<div class="row">
    <div class="span12 page-title">
        <h2>Plans</h2>
        <hr>
    </div>

</div>

<div class="row">
    <div class="span4">
        <div class="page-main-icon page-buy-icon">
            <i class="icon-shopping-cart"></i>
        </div>
    </div>
    <div class="span8"><p>
        Credit is the basic unit of purchase that you can buy from MSNGR. A credit allows you to send a single text
        message (sms)
        of 160 characters or less. If you send a message that is beyond 160 characters, you will be charged two credits.
        Messages
        beyond 320 characters are not allowed as per TRAI policies.
    </p>

        <p>
            MSNGR.IN offers multiple plans to fit your needs. You can buy the plan that fulfills your requirements
            without
            having to buy limited or excess credits. The plans become more economical as you go upwards. Each plan
            requires one time payment
            and there are no monthly or recurring costs. Once you use up the credits, you can purchase more credits are
            per plans available & top up your account.
        </p>

        <p>We also provide a <b>Free Plan</b> so that you can try the service and see for yourself how easy it makes your life.
            You get 100 credits free when you sign up which you can use to send SMS to your teachers and parents.</p>

        <h3>Plans</h3>

        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>Credits</th>
                <th>Price (Rs.)</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>100</td>
                <td>Free on Sign up</td>
                <td><a target="_blank" href="http://app.msngr.in/#/user/register" class="btn btn-small btn-success">Sign
                    Up Now</a></td>
            </tr>
            <?php foreach ($plans as $plan): ?>

            <tr>
                <td><% $plan["credits"] %></td>
                <td><% $plan["price"] %></td>
                <td><a href="/plan/<% $plan["id"] %>" class="btn btn-small">Buy This</a></td>
            </tr>
                <?php endforeach; ?>

            </tbody>

        </table>


    </div>
</div>

@stop