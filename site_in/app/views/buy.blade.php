@extends('layouts.common')

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
        Credit is the basic unit of purchase which MSNGR.IN sells. Each credit allows you to send a single text message
        of 160 characters or less. If you send message beyond 160 characters, you are debited two credits. Messages
        beyond 320 characters are not allowed as per TRAI policies.
    </p>

        <p>
            MSNGR.IN offer multiple plans to fit your needs. You can buy the plant that fulfil your requirements without
            having to buy limited or excess credits. The plans become more economical as you go upwards. Each plan
            requires one time payment
            and requires no monthly or recurring costs. Once you consume the credits, you can purchase more credits are
            per plans available & top up your account.
        </p>

        <h3>Plans</h3>

        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>SMSs</th>
                <th>Price (Rs.)</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($plans as $plan): ?>

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