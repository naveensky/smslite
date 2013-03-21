<div class="row">
    <div class="span3">
        <div class="box" style="padding: 8px 0;">
            @render('admin.leftmenu')
        </div>
    </div>

    <div class="span9">
        <div class="box">
            <h3><i class="icon-money icon-large"></i>Allocate Credits</h3>

            <div class="row">
                <div class="span8">
                    <form name="form" novalidate>

                        <label for="schoolName">Choose School</label>
                        <select class="school-select" ng-model="schoolSelected" ng-required="true" name="schoolName"
                                id="schoolName">
                            <option value="0">Select School</option>
                            <option ng-repeat="school in schools" value="{{school.code}}">{{school.name}}</option>
                        </select>
                                                    <span
                                                        ng-show="form.schoolName.$error.required && !form.schoolName.$pristine "
                                                        class="validation invalid"><i
                                                            class="icon-remove padding-right-5"></i>School name is required</span>


                        <label for="inputContactPerson">Contact Person</label>


                        <input type="text" class="span4" id="inputContactPerson" ng-required="true" name="contactPerson"
                               ng-model="profile.contactPerson" placeholder="Contact Person">
                            <span ng-show="form.contactPerson.$error.required && !form.contactPerson.$pristine "
                                  class="validation invalid"><i class="icon-remove padding-right-5"></i>Contact person name is required</span>


                        <label for="inputAddress">Address</label>


                        <textarea rows="5" class="span4" id="inputAddress" name="address" ng-required="true"
                                  ng-model="profile.address" placeholder="Address"></textarea>
                            <span ng-show="form.address.$error.required && !form.address.$pristine "
                                  class="validation invalid"><i class="icon-remove padding-right-5"></i>Address is required</span>


                        <label for="inputSchoolContactNumber">School Contact Number</label>


                        <input type="text" class="span4" id="inputSchoolContactNumber" name="schoolContact"
                               ng-required="true"
                               ng-model="profile.contactMobile" ng-minLength="8" ng-pattern="/^\+{0,1}\d+$/"
                               placeholder="School Contact Number">
                            <span ng-show="form.schoolContact.$error.required && !form.schoolContact.$pristine"
                                  class="validation invalid"><i class="icon-remove padding-right-5"></i>Contact number is required</span>
                            <span
                                ng-show="form.schoolContact.$invalid && !form.schoolContact.$pristine && !form.schoolContact.$error.required"
                                class="validation invalid"><i
                                    class="icon-remove padding-right-5"></i>The contact number must be at least 8 digits</span>


                        <label for="inputCity">City</label>


                        <input type="text" class="span4" id="inputCity" ng-required="true" name="city"
                               ng-model="profile.city"
                               placeholder="City">
                            <span ng-show="form.city.$error.required && !form.city.$pristine"
                                  class="validation invalid"><i class="icon-remove padding-right-5"></i>City is required</span>


                        <label for="inputState">State</label>


                        <input type="text" class="span4" id="inputState" ng-required="true" name="state"
                               ng-model="profile.state"
                               placeholder="State">
                            <span ng-show="form.state.$error.required && !form.state.$pristine"
                                  class="validation invalid"><i class="icon-remove padding-right-5"></i>State is required</span>


                        <label for="inputZip">Zip</label>

                        <input type="text" class="span4" id="inputZip" ng-required="true" name="zip"
                               ng-model="profile.zip"
                               placeholder="Zip">
                            <span ng-show="form.zip.$error.required && !form.zip.$pristine"
                                  class="validation invalid"><i class="icon-remove padding-right-5"></i>Pin Code is required</span>

                        <!--                        <input type="hidden" ng-init="senderId='-->
                        <?php //echo $senderId;?><!--'">-->

                        <div class="controls margin-top-20">
                            <div class="alert alert-success margin-top-20" style="width:35%"
                                 ng-show="schoolUpdateSuccess">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                Profile successfully updated
                            </div>
                            <div class="alert alert-error margin-top-20" style="width:45%"
                                 ng-show="schoolUpdateError">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                Sorry your profile is not updated please try again
                            </div>
                            <button type="button" ng-click="updateProfile()" ng-disabled="form.$invalid"
                                    class="btn btn-success">
                                Update
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

