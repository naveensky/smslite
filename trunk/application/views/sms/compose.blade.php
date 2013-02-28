<div class="row">
    <div class="span4">
        <div class="box">
            <h3><i class="icon-group icon-large"></i> Select People</h3>
            <label>Choose Filter</label>
            <select ng-model="filterType">
                <option value="classFilter">By Classes</option>
                <option value="routeFilter">By Bus Routes</option>
                <option value="departmentFilter">By Departments</option>
                <option value="individualFilter">Search Individual</option>
            </select>

            <hr>
            <div id="filter-individual" ng-show="filterType=='individualFilter'">
                <label>Enter a Name or a Mobile No.</label>

                <div class="input-append">
                    <input type="text" class="span3">
                    <button class="btn" type="button"><i class="icon-search"></i></button>
                </div>
                <table class="table table-condensed">
                    <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th>Name</th>
                        <th>Phone</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><input type="checkbox"></td>
                        <td>Naveen Gupta</td>
                        <td>9891410701, +</td>
                    </tr>
                    <tr>
                        <td><input type="checkbox"></td>
                        <td>Naveen Gupta</td>
                        <td>9891410701, +</td>
                    </tr>
                    <tr>
                        <td><input type="checkbox"></td>
                        <td>Naveen Gupta</td>
                        <td>9891410701 +</td>
                    </tr>
                    </tbody>
                </table>
                <button class="btn">Add</button>
            </div>

            <div id="filter-class" ng-show="filterType=='classFilter'">
                <label>Choose classes</label>
            </div>
            <div id="filter-department" ng-show="filterType=='departmentFilter'">
                <label>Choose Departments</label>
            </div>
            <div id="filter-route" ng-show="filterType=='routeFilter'">
                <label>Choose Routes</label>
            </div>

        </div>
    </div>
    <div class="span5">
        <div class="box">
            <h3><i class="icon-envelope-alt icon-large"></i>Compose Message</h3>

            <textarea class="input-block-level" rows="5" ng-model="message"
                      placeholder="enter your message here..."></textarea>
            <span ng-show="message.length>0" class="help-block">
                <i>
                    {{message.length}} character, {{getSingleMessageCredit()}} credit(s) required per person to
                    send this text.
                </i>
            </span>

        </div>
        <div class="box">
            <h3>People in List</h3>
            <table class="table table-condensed table-hover">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>&nbsp;</th>
                </tr>
                </thead>
                <tbody>
                <tr ng-repeat="person in selectedPeople">
                    <td>{{person.name}}</td>
                    <td><span title="{{person.mobiles.join('\n')}}"> {{person.mobiles[0]}}</span></td>
                    <td><a class="pull-right" ng-click="removePerson(person)"><i class="icon-remove"></i> Remove</a>
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="pagination">
                <ul>
                    <li><a href="#">&laquo;</a></li>
                    <li><a href="#">&raquo;</a></li>
                </ul>
            </div>

        </div>
    </div>
    <div class="span3">
        <div class="box">
            <h3><i class="icon-cog icon-large"></i>Verify &amp; Send</h3>

            <div class="control-group">
                <label>Total Individual</label>
                <input class="input-block-level" type="text" readonly="readonly" value="{{getPeopleCount()}}">
            </div>
            <div class="control-group">
                <label>Total SMS to deliver</label>
                <input class="input-block-level" type="text" readonly="readonly" value="{{totalSMS()}}">
            </div>
            <div class="control-group">
                <label>Credits Required</label>
                <input class="input-block-level" type="text" readonly="readonly" value="{{getCreditsRequired()}}">
            </div>
            <div class="control-group">
                <label>Credits Available <a href="#" class="pull-right">
                        <small><i class="icon-money"></i> Buy Credits</small></label>
                </a>
                <input class="input-block-level" type="text" readonly="readonly" ng-model="getSingleMessageCredit">
            </div>
            <button class="btn btn-block btn-success"><i class="icon-add"></i> Add to SMS Queue</button>
        </div>
    </div>
</div>
