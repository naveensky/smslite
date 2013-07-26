<section class="container">
    <div class="row">
        <div class="span3">

            <div class="box" style="padding: 8px 0;">
                @render('admin.leftmenu')
            </div>
            <div class="box">
                <form ng-submit="filterSMS()">
                <label><strong>From Date</strong></label>

                <div class="input-append date date-input">
                    <input class="span2" type="text" id="dpd1" ng-model="fromDate" readonly>
                    <span class="add-on"><i class="icon-calendar"></i></span>
                </div>

                <label><strong>To Date</strong></label>

                <div class="input-append date date-input">
                    <input class="span2" type="text" id="dpd2" ng-model="toDate" readonly>
                    <span class="add-on"><i class="icon-calendar"></i></span>
                </div>

                <label><strong>SMS Status</strong></label>
                <select name="status" ng-model="status">
                    <option value=''>Select Status</option>
                    <option value='pending'>Pending</option>
                    <option value='sent'>Sent</option>
                    <option value='fail'>Fail</option>
                </select>

                <label for="schoolName">Choose School</label>
                <select ng-model="selectedSchool" name="schoolName"
                        id="schoolName">
                    <option value="">Select School</option>
                    <option ng-repeat="school in schools" value="{{school.code}}">{{school.name}}</option>
                </select>

                <input type="submit" class="btn btn-primary" value="Filter">
                </form>
            </div>
        </div>
        <div class="span9">
            <div class="box">
                <!-- Recent users email-verify -->
                <div id="container" style="min-width: 400px; height: 300px; margin: 0 auto"></div>
            </div>
            <div class="box">
                <!-- Recent users email-verify -->
                <h3 class="margin-top-0"><i class="icon-file-alt icon-large padding-right-5"></i>Reports</h3>

                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th class="width-20">School</th>
                        <th class="width-15">Status</th>
                        <th class="width-15">Name</th>
                        <th class="width-15">Mobile</th>
                        <th class="width-40">Message</th>
                        <th class="width-15">Queue</th>
                        <th class="width-15">Sent Time</th>

                    </tr>
                    </thead>
                    <tbody ng-show="smsRows.length>0">

                    <tr ng-repeat="smsRow in smsRows" ng-class="getStatusCss(smsRow)">
                        <td class="width-20">{{ smsRow.school }}</td>
                        <td class="width-15">{{ smsRow.status }}</td>
                        <td class="width-15">{{ smsRow.name }}</td>
                        <td class="width-15">{{ smsRow.mobile }}</td>
                        <td class="width-30">{{ smsRow.message }}</td>
                        <td class="width-20">{{ getFormattedDate(smsRow.queueTime,false) }}</td>
                        <td class="width-20"><span ng-show="smsRow.status=='sent'">{{getFormattedDate(smsRow.sentTime,false)}}</span>
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
</section>
<script type="text/javascript">
    initComponents();

</script>

