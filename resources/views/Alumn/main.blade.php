@extends('layout')

@section('content')
	
	<div class="loader-classroom">
	  <div class="lds-circle"><div></div></div>
	</div>
	
	<div class="wrapper">
		@include('Alumn.header')
		@include('Alumn.aside')
		@yield('content-alumn')
		@include('Alumn.footer')
	</div>

@stop