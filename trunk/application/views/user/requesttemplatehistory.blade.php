<div class="row">
    <div class="span3">
        <div class="box" style="padding: 8px 0;">
            @render('user.leftmenuaccountinfo')
        </div>
    </div>

    <div class="span9">
        <div class="box">


            <h3><i class="icon-user icon-large"></i>Requested Templates History</h3>

            <div class="row">
                <div class="span8">
                    <p>The status of all the templates you have requested.</p>
                    <table class="table table-striped table-hover table-condensed">
                        <thead>
                        <tr>
                            <th>Template Name</th>
                            <th>Template Body</th>
                            <th>Is Approved</th>
                            <th>Submit Date</th>
                        </tr>
                        </thead>
                        <tbody ng-show="requestedTemplatesHistory.length>0">

                        <tr ng-repeat="requestedHistory in requestedTemplatesHistory" ng-class="getStatusCss(smsRow)">
                            <td>{{ requestedHistory.templateName }}</td>
                            <td>{{ requestedHistory.templateBody }}</td>
                            <td><i class="icon {{getTemplateStatusClass(requestedHistory.isApproved)}}"</td>
                            <td>{{getFormattedDate(transaction.created_at)}}</td>

                        </tr>

                        </tbody>
                        <tbody ng-show="requestedTemplatesHistory.length==0">
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

