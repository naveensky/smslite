<div class="row">
    <div class="span3">
        <div class="box" style="padding: 8px 0;">
            @render('user.leftmenuaccountinfo')
        </div>
    </div>

    <div class="span9">
        <div class="box">


            <h3><i class="icon-user icon-large"></i>Transaction History </h3>

            <div class="row">
                <div class="span8">
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been
                        the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley
                        of type and scrambled it to make a type specimen book.</p>
                    <table class="table table-striped table-hover table-condensed">
                        <thead>
                        <tr>
<!--                            <th>Order ID</th>-->
                            <th>SMSCredits</th>
                            <th>Amount</th>
                            <th>Discount</th>
                            <th>Gross Amount</th>
                            <th>Order Date</th>
                        </tr>
                        </thead>
                        <tbody ng-show="transactionsHistory.length>0">

                        <tr ng-repeat="transaction in transactionsHistory">
<!--                            <td>{{ transaction.orderId }}</td>-->
                            <td>{{ transaction.smsCredits }}</td>
                            <td>{{ transaction.amount }}</td>
                            <td>{{ transaction.discount }}</td>
                            <td>{{ transaction.grossAmount }}</td>
                            <td>{{getFormattedDate(transaction.created_at)}}</td>

                        </tr>

                        </tbody>
                        <tbody ng-show="students.length==0">
                        <tr>
                            <td colspan="9" style="text-align: center">
                                <br/>
                                <strong>
                                    No Data Found
                                </strong>
                                <br/>
                                <br/>
                            </td>
                        </tr>
                        </tbody>

                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

