<section class="container">
    <div class="row">
        <div class="span3">
            <div class="box" style="padding: 8px 0;">
                <div ng-repeat="class in classes">
                    <label class="checkbox">
                        <input type="checkbox" ng-model="class.selected" ng-click="setClassSections(this,class)">
                        {{class.class}} {{class.selected}}
                    </label>
                </div>
                {{classSections}}
                <button class="btn btn-primary" ng-click="filterSMS()">Filter</button>
            </div>
        </div>
        <div class="span9">
            <div class="box">
                <!-- Recent users email-verify -->

                <div id="container" style="min-width: 400px; height: 200px; margin: 0 auto"></div>
            </div>
            <div class="box">
                <!-- Recent users email-verify -->
                <h3 class="margin-top-0"><i class="icon-file-alt icon-large padding-right-5"></i>Reports</h3>
                <table class="table table-striped table-hover table-condensed">
                    <thead>
                    <tr>
                        <th class="width-20">Name</th>
                        <th class="width-15">Mobile No.</th>
                        <th class="width-40">Message</th>
                        <th class="width-15">Queue</th>
                        <th class="width-15">Sent Time</th>
                    </tr>
                    </thead>
                    <tbody ng-show="smsRows.length>0">

                    <tr ng-repeat="smsRow in smsRows" ng-class="getStatusCss(smsRow)">
                        <td class="width-20">{{ smsRow.name }}</td>
                        <td class="width-15">{{ smsRow.mobile }}</td>
                        <td class="width-40">{{ smsRow.message }}</td>
                        <td class="width-15">{{ smsRow.queue_time }}</td>
                        <td class="width-15" ng-show="smsRow.status=='sent'">{{ smsRow.sent_time }}</td>
                    </tr>
                    </tbody>
                    <tbody ng-show="smsRows.length==0">
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
                <div>
                    <button class="btn" ng-disabled="previousPage == 0" ng-click="updatePrevious()"><i
                            class="icon-caret-left icon-large"></i></button>
                    <button class="btn" ng-disabled="smsRows.length ==0" ng-click="updateNext()"><i
                            class="icon-caret-right icon-large"></i></button>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>