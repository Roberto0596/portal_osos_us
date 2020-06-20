@extends('layout')

@section('content')
	
	<div class="wrapper">
		@include('Alumn.header')
		@include('Alumn.aside')
		@yield('content-alumn')
	</div>

@stop