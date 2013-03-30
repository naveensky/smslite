@if(count($knownVariables)!=0 || count($messageVars)!=0)
<table class="table table-condensed table-bordered">
    <tbody>
    @foreach ($knownVariables as $key => $value)
    <tr>
        <td><strong><%trim($key)%></strong></td>
        <td><%trim($value)%></td>
    </tr>

    @endforeach
    </tbody>
</table>
@foreach ($messageVars as $key => $value)
<div class="control-group">
    <label><%$value%></label><input type="text" ng-model="model.<% $key %>"/>
</div>
@endforeach
@endif
@if(count($knownVariables)==0 && count($messageVars)==0)
No placeholders for the above message
@endif






