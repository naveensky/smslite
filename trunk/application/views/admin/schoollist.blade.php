<section class="container">
    <div class="row">
        <div class="span3">
            <div class="box" style="padding: 8px 0;">
                @render('admin.leftmenu')
            </div>
            <div class="box">
                <form ng-submit="filterSchool()">
                    <label><strong>Name</strong></label>

                    <input class="input-large" type="text" name="name" ng-model="name"/>


                    <label><strong>Email</strong></label>

                    <input class="input-large" type="text" name="email" ng-model="email">

                    <label><strong>Registration Date</strong></label>

                    <div class="input-append date date-input">
                        <input class="span2" type="text" id="registeredDate" ng-model="registrationDate" readonly>
                        <span class="add-on"><i class="icon-calendar"></i></span>
                    </div>


                    <input type="submit" class="btn btn-primary" value="Filter">
                </form>
            </div>
        </div>

        <div class="span9">
            <div class="box">
                <!-- Recent users email-verify -->
                <h3 class="margin-top-0"><i class="icon-file-alt icon-large padding-right-5"></i>Schools List</h3>

                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th class="width-20">School Name</th>
                        <th class="width-15">Contact Person</th>
                        <th class="width-15">Contact Number</th>
                        <th class="width-40">Email</th>
                        <th class="width-15">Account Create Date</th>
                        <th class="width-15">Remaining Credits</th>
                        <th class="width-15">SMS Pending</th>
                        <th class="width-15">SMS Sent</th>
                    </tr>
                    </thead>
                    <tbody ng-show="schoolList.length>0">

                    <tr ng-repeat="value in schoolList">
                        {{value.created_at}}
                        <td class="width-20">{{ value.name }}</td>
                        <td class="width-15">{{ value.contactPerson }}</td>
                        <td class="width-15">{{ value.contactMobile }}</td>
                        <td class="width-30">{{ value.email }}</td>
                        <td class="width-20">{{ getFormattedDate(value.created_at,true) }}</td>
                        <td class="width-20">{{value.credits}}</td>
                        <td class="width-20">{{ value.pendingSMS }}</td>
                        <td class="width-20">{{ value.sentSMS }}</td>

                    </tr>
                    </tbody>
                    <tbody ng-show="schoolList.length==0">
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
                    <button class="btn" ng-disabled="schoolPreviousPage == 0" ng-click="updatePreviousSchool()"><i
                            class="icon-caret-left icon-large"></i></button>
                    <button class="btn" ng-disabled="schoolList.length==0" ng-click="updateNextSchool()"><i
                            class="icon-caret-right icon-large"></i></button>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
    initComponents();

</script>

