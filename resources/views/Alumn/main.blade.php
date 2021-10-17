@extends('layout')

@section('content')

	<link rel="stylesheet" href="{{ asset('css/loader_log.css') }}">

	<div class="loader-classroom">
	  	<div class="lds-circle"><div></div></div>
	</div>
	
	<div class="wrapper">

		@include('Alumn.header')
		@include('Alumn.aside')
		@yield('content-alumn')
		@include('Alumn.footer')
		
	</div>

	@include('Alumn.pusher')

	<script src="{{ asset('js/loaderClassroom.js') }}"></script>

@stop