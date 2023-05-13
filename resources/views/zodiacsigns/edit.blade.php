@extends('layouts.app')

@section('content')

	@if($errors->any())
		<div class="alert alert-danger">
			@foreach ($errors->all() as $error)
				{{ $error }} <br>
			@endforeach
		</div>
	@endif

	{{ Form::model($zodiacsign, array('route' => array('zodiacsigns.update', $zodiacsign->id), 'method' => 'PUT')) }}

		<div class="mb-3">
			{{ Form::label('Aries', 'Aries', ['class'=>'form-label']) }}
			{{ Form::text('Aries', null, array('class' => 'form-control')) }}
		</div>
		<div class="mb-3">
			{{ Form::label('Taurus', 'Taurus', ['class'=>'form-label']) }}
			{{ Form::text('Taurus', null, array('class' => 'form-control')) }}
		</div>
		<div class="mb-3">
			{{ Form::label('Gemini', 'Gemini', ['class'=>'form-label']) }}
			{{ Form::text('Gemini', null, array('class' => 'form-control')) }}
		</div>
		<div class="mb-3">
			{{ Form::label('Cancer', 'Cancer', ['class'=>'form-label']) }}
			{{ Form::text('Cancer', null, array('class' => 'form-control')) }}
		</div>
		<div class="mb-3">
			{{ Form::label('Leo', 'Leo', ['class'=>'form-label']) }}
			{{ Form::text('Leo', null, array('class' => 'form-control')) }}
		</div>
		<div class="mb-3">
			{{ Form::label('Virgo', 'Virgo', ['class'=>'form-label']) }}
			{{ Form::text('Virgo', null, array('class' => 'form-control')) }}
		</div>
		<div class="mb-3">
			{{ Form::label('Libra', 'Libra', ['class'=>'form-label']) }}
			{{ Form::text('Libra', null, array('class' => 'form-control')) }}
		</div>
		<div class="mb-3">
			{{ Form::label('Scorpio', 'Scorpio', ['class'=>'form-label']) }}
			{{ Form::text('Scorpio', null, array('class' => 'form-control')) }}
		</div>
		<div class="mb-3">
			{{ Form::label('Sagittarius', 'Sagittarius', ['class'=>'form-label']) }}
			{{ Form::text('Sagittarius', null, array('class' => 'form-control')) }}
		</div>
		<div class="mb-3">
			{{ Form::label('Capricorn', 'Capricorn', ['class'=>'form-label']) }}
			{{ Form::text('Capricorn', null, array('class' => 'form-control')) }}
		</div>
		<div class="mb-3">
			{{ Form::label('Aquarius', 'Aquarius', ['class'=>'form-label']) }}
			{{ Form::text('Aquarius', null, array('class' => 'form-control')) }}
		</div>
		<div class="mb-3">
			{{ Form::label('Pisces', 'Pisces', ['class'=>'form-label']) }}
			{{ Form::text('Pisces', null, array('class' => 'form-control')) }}
		</div>

		{{ Form::submit('Edit', array('class' => 'btn btn-primary')) }}

	{{ Form::close() }}
@stop
