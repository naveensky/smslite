<div class="row">
    <div class="span3">
        <div class="box" style="padding: 8px 0;">
            @render('user.leftmenuaccountinfo')
        </div>
    </div>

    <div class="span9">
        <div class="box">
            <h3><i class="icon-user icon-large"></i>Request New Template</h3>

            <div class="row">
                <div class="span8">
                    <form name="form" novalidate>
                        <div class="control-group">
                            <label for="templateName">Template Name</label>
                            <input type="text" id="templateName" name="templateName" ng-required="true"
                                   ng-model="templateName"
                                   placeholder="Enter name of the template">
                        <span ng-show="form.templateName.$error.required && !form.templateName.$pristine"
                              class="validation invalid"><i class="icon-remove padding-right-5"></i>Please enter template name</span>
                        </div>
                        <div class="control-group">
                            <label>Template Body</label>
                            <textarea class="input-block-level" rows="6"
                                      name="message"
                                      ng-model="templateBody"
                                      ng-required="true"
                                      placeholder="enter your message here..."></textarea>
                            <span ng-show="form.templateBody.$error.required && !form.templateBody.$pristine"
                                  class="validation invalid"><i class="icon-remove padding-right-5"></i>Please enter template body</span>
            <span ng-show="templateBody.length>0" class="help-block">
                <i>
                    {{templateBody.length}} character, {{getSingleMessageCredit()}} credit(s) required per person to
                    send this text.
                </i>
            </span>
            <span ng-show="templateBody.length>320" class="text-error">
                <i>
                    maximum character limit exceeded {{320-templateBody.length}}
                </i>
            </span>
                        </div>
                        <div class="controls margin-top-20">
                            <label class="checkbox">
                                <input type="checkbox" ng-model="iAgree">I verified the above information</a>
                             <span ng-show="!iAgree"
                                   class="validation invalid"><br/><i class="icon-remove padding-right-5"></i>In order to request new template you must verified the above information</span>
                            </label>

                            <div class="alert alert-success margin-top-20" style="width:35%"
                                 ng-show="successUpdatePassword">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                {{message}}
                            </div>
                            <div class="alert alert-error margin-top-20" style="width:45%"
                                 ng-show="errorUpdatePassword">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                {{message}}
                            </div>
                            <button type="button" ng-click="requestTemplate()" ng-disabled="form.$invalid || !iAgree || templateBody.length>320"
                                    class="btn btn-success">
                                Request Now
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

