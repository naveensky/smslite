<section class="container" style="margin-top:20px;">
    <div class="box">
        <!--<div class="tabbable">-->

        <!-- Tabs
       ================================================== -->
        <ul class="nav nav-tabs" id="register-tabs">
            <li><a href="#">Sign up</a></li>
            <li class="active"><a href="#">School Information</a></li>
            <li><a href="#">Verify Mobile</a></li>
            <li><a href="#">Verify Email</a></li>
            <li><a href="#">Welcome</a></li>
        </ul>
        <!-- / Tabs -->

        <!-- Tabs content
       ================================================== -->
        <div class="tab-content">

            <!-- Recent comments tab content -->
            <div class="tab-pane fade in active" id="school-information">

                <form class="form-horizontal offset2">
                    <div class="control-group">
                        <label class="control-label" for="inputSchoolName">School Name</label>

                        <div class="controls">
                            <input type="text" id="inputSchoolName" ng-model="schoolName" placeholder="School Name">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="inputContactPerson">Contact Person</label>

                        <div class="controls">
                            <input type="text" id="inputContactPerson" ng-model="contactPerson" placeholder="Contact Person">
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="inputAddress">Address</label>

                        <div class="controls">
                            <textarea rows="3" id="inputAddress" ng-model="address" placeholder="Address"></textarea>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="inputSchoolContactNumber">School Contact Number</label>

                        <div class="controls">
                            <input type="text" id="inputSchoolContactNumber" ng-model="schoolContactNumber" placeholder="School Contact Number">
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="inputCity">City</label>

                        <div class="controls">
                            <input type="text" id="inputCity" ng-model="city" placeholder="City">
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="inputState">State</label>

                        <div class="controls">
                            <input type="text" id="inputState" ng-model="state" placeholder="State">
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="inputZip">Zip</label>

                        <div class="controls">
                            <input type="text" id="inputZip" ng-model="zip" placeholder="Zip">
                        </div>
                    </div>
                    <input type="hidden" ng-init="senderId='<?php echo $senderId;?>'">

                    <div class="controls margin-top-20">
                        <button type="button" ng-click="saveSchoolInfo()" class="btn btn-success">Next</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- / Tabs content -->
    </div>
    <!--</div>-->
</section>