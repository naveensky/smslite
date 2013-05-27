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
                            <th class="width-20">Template Name</th>
                            <th class="width-40">Template Body</th>
                            <th class="width-15">Status</th>
                            <th class="width-15">Submit Date</th>
                        </tr>
                        </thead>
                        <tbody ng-show="requestedTemplatesHistory.length>0">

                        <tr ng-repeat="requestedHistory in requestedTemplatesHistory"
                            ng-class="getStatusCss(requestedHistory)">
                            <td class="width-20">{{ requestedHistory.name }}</td>
                            <td class="width-40">{{ requestedHistory.body }}</td>
                            <td class="width-15">{{getTemplateStatusMessage(requestedHistory.status)}}</td>
                            <td class="width-15">{{getFormattedDate(requestedHistory.created_at)}}</td>

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

