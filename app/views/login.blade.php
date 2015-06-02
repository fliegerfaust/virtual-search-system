@extends('layouts.master')

@section('content')
<div class="row">
    <div class="large-6 large-centered column">
	{{ Form::open(array('url' => 'login')) }}
	 <fieldset>
		<legend>Login</legend>

		@if($errors->has())
            @foreach ($errors->all() as $message) 
                <span class="label alert round">{{$message}}</span><br>
            @endforeach
        @endif

		{{ Form::label('username', 'Username') }}
		{{ Form::text('username', Input::old('username'), array('placeholder' => '')) }}

		{{ Form::label('password', 'Password') }}
		{{ Form::password('password') }}

		{{ Form::submit('Submit', ['class' => 'button']) }}

	 </fieldset>
	{{ Form::close() }}
	@stop