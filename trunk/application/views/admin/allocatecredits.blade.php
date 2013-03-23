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
                                                        ng-show="form.schoolName.$error.required"
                                                        class="validation invalid"><i
                                                            class="icon-remove padding-right-5"></i>Please Select the school</span>


                        <label for="inputCreditsAllocate">Credits to Allocate</label>
                        <input type="text" class="span4" id="inputCreditsAllocate" ng-required="true"
                               name="allocateCredits"
                               ng-model="allocateCredits" placeholder="Credits to allocate">
                            <span ng-show="form.allocateCredits.$error.required && !form.allocateCredits.$pristine "
                                  class="validation invalid"><i class="icon-remove padding-right-5"></i>Please enter the credits to allocate</span>
                        <span ng-show="allocateCredits>10000" class="validation invalid">Credits to be allocate should be less than 10,000</span>

                        <label for="inputAmount">Amount</label>
                        <input type="text" class="span4" id="inputAmount" ng-required="true" name="amount"
                               ng-model="amount"
                               placeholder="Amount">
                            <span ng-show="form.amount.$error.required && !form.amount.$pristine"
                                  class="validation invalid"><i class="icon-remove padding-right-5"></i>Amount is required</span>


                        <label for="inputDiscount">Discount</label>
                        <input type="text" class="span4" id="inputDiscount" name="discount"
                               ng-model="discount"
                               placeholder="Discount">


                        <label for="inputGrossAmount">Gross Amount</label>
                        <input type="text" class="span4" id="inputGrossAmount" ng-required="true" name="discount"
                               readonly="readonly" value="{{getGrossAmount()}}">
                        <label for="inputRemarks">Remarks</label>
                        <textarea rows="5" class="span4" id="inputRemarks" name="remarks"
                                  ng-model="remarks" placeholder="Enter remarks related to transaction"></textarea>


                        <div class="control-group">
                            <label class="checkbox">
                                <input type="checkbox" ng-model="notifySchool">Notify School
                            </label>

                            <div class="alert alert-success margin-top-20" style="width:35%"
                                 ng-show="showSuccess">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                {{message}}
                            </div>
                            <div class="alert alert-error margin-top-20" style="width:45%"
                                 ng-show="showError">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                {{message}}
                            </div>
                            <button type="button" ng-click="creditsAllocate()"
                                    ng-disabled="form.$invalid || allocateCredits>100000 || showSuccess"
                                    class="btn btn-success">
                                Allocate
                            </button>
                        </div>
                </div>
                </form>
            </div>

        </div>
    </div>
</div>


