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






