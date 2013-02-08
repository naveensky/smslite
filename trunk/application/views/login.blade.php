@layout('templates.main')
@section('content')
<div class="span4 well">
    {{ Form::open('auth/validate_login') }}
    <!-- check for login errors flash var -->
    @if (Session::has('login_errors'))
     <span class="error">Username or password incorrect.</span>
     @endif
    <!-- username field -->
    <p>{{ Form::label('email', 'Username') }}</p>
    <p>{{ Form::text('email') }}</p>
    <!-- password field -->
    <p>{{ Form::label('password', 'Password') }}</p>
    <p>{{ Form::password('password') }}</p>
    <!-- submit button -->
    <p>{{ Form::submit('Login', array('class' => 'btn-large')) }}</p>
    {{ Form::close() }}
</div>
@endsection