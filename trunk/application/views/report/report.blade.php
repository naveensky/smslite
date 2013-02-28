<section class="container">
    <div class="row">
        <div class="span3">
            <div class="box">
                <label><strong>Queue Date</strong></label>

                <div class="input-append date date-input" data-date-format="dd M yyyy">
                    <input class="span2" type="text" ng-model="queueDate" id="dpd1" readonly="readonly">
                    <span class="add-on"><i class="icon-calendar"></i></span>
                </div>
                <label><strong>Sent Date</strong></label>

                <div class="input-append date date-input">
                    <input class="span2" type="text" ng-model="sentDate" id="dpd2" readonly>
                    <span class="add-on"><i class="icon-calendar"></i></span>
                </div>
                <label><strong>Search by student</strong></label>
                <input type="text" class="width-86" ng-model="studentName" placeholder="Student name..">
                <label><strong>Search by teacher</strong></label>
                <input type="text" class="width-86" ng-model="teacherName" placeholder="Teacher name..">
                <label><strong>Select Classes</strong></label>
                <div ng-repeat="class in classes">
                    <label class="checkbox">
                        <input type="checkbox" ng-model="class.selected"
                               ng-click="setClassSections(class.class,class.selected)">
                        {{class.class}}
                    </label>
                </div>
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
                <table class="table table-striped">
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
                        <td class="width-30">{{ smsRow.message }}</td>
                        <td class="width-20">{{ getFormattedDate(smsRow.queue_time) }}</td>
                        <td class="width-20"><span ng-show="smsRow.status=='sent'">{{getFormattedDate(smsRow.sent_time)}}</span>
                        </td>
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
<script type="text/javascript">
    initComponents();
</script>

